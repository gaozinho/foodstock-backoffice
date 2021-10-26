<?php

namespace App\Actions\Product\Ifood;

use App\Models\Product;
use App\Models\Restaurant;
use App\Integrations\IfoodIntegrationDistributed;

use App\Enums\BrokerType;

use App\Models\Category;
use App\Models\Catalog;
use App\Models\Item;
use App\Models\Option;
use App\Models\OptionGroup;
use App\Models\DietaryRestriction;

use App\Actions\Product\Ifood\BrokerProducts;
use Illuminate\Support\Facades\DB;

class BrokerCatalog
{

    protected $integration;
    protected $brokerProducts;
    protected $restaurant;

    public function __construct(Restaurant $restaurant)
    {
        $this->integration = new IfoodIntegrationDistributed();
        $this->brokerProducts = new BrokerProducts();
        $this->restaurant = $restaurant;
    }

    public function prepareProcessing(){
        //Apagar toda organização
        Category::where("broker_id", BrokerType::Ifood)->where("restaurant_id", $this->restaurant->id)->delete();
        OptionGroup::where("broker_id", BrokerType::Ifood)->where("restaurant_id", $this->restaurant->id)->delete();

    }

    public function processCatalogs($catalogs){
        //$this->prepareProcessing();

        if(is_array($catalogs)){
            foreach($catalogs as $catalog){
                $categories = $this->integration->getCategories($this->restaurant->id, $catalog->catalogId);
                $this->processCategories($categories);
            }
        }
    }

    public function processCatalog($catalogId){
        //$this->prepareProcessing();

        //if(is_array($catalogs)){
            //foreach($catalogs as $catalog){
                $categories = $this->integration->getCategories($this->restaurant->id, $catalogId);
                $this->processCategories($categories);
            //}
        //}
    }    

    public function processCategories($categories){
        foreach($categories as $category){
            $categoryModel = Category::create([
                "broker_id" => BrokerType::Ifood,
                "name" => $category->name,
                "external_code" => $category->externalCode ?? null,
                "enabled" => 1,
                "sequence" => $category->sequence,
                "index" => $category->index,
                "user_id" => $this->restaurant->user_id,
                "restaurant_id" => $this->restaurant->id,
            ]);

            $this->processItems($category->items, $categoryModel);
            
        }
    }

    public function processItems($items, Category $category){
        foreach($items as $item){

            //Resolve master item product
            $product = $this->brokerProducts->processOne($item, $this->restaurant);

            $itemModel = Item::create([
                "product_id" => $product->id,
                "category_id" => $category->id,
                "external_code" => $item->externalCode ?? null,
                "serving" => intval(str_replace("SERVES_", "", $item->serving ?? 0)),
            ]);

            $this->processDietaryRestrictions($item->dietaryRestrictions ?? [], $itemModel);

            $this->processOptionGroups($item->optionGroups ?? [], $itemModel);
        }
    }

    public function processDietaryRestrictions($dietaryRestrictions, Item $item){
        foreach($dietaryRestrictions as $dietaryRestriction){
            DietaryRestriction::create([
                "item_id" => $item->id,
                "name" => $dietaryRestriction
            ]);
        }
    }

    public function processOptionGroups($optionGroups, Item $item){

        $optionGroupIds = [];
        foreach($optionGroups as $optionGroup){
            $optionGroupModel = OptionGroup::create([
                "external_code" => $optionGroup->externalCode ?? null,
                "name" => $optionGroup->name,
                "min" => $optionGroup->min,
                "max" => $optionGroup->max,
                "sequence" => $optionGroup->sequence,
                "index" => $optionGroup->index,
                "broker_id" => BrokerType::Ifood,
                "restaurant_id" => $this->restaurant->id,
            ]);
            $optionGroupIds[] = $optionGroupModel->id;
            $this->processOptions($optionGroup->options ?? [], $optionGroupModel);
        }  

        //Vincular itens aos grupos de opções
        $item->optionGroups()->sync($optionGroupIds);
    }   
    
    public function processOptions($options, OptionGroup $optionGroup){
        foreach($options as $option){
            //Resolve master item product
            $product = $this->brokerProducts->processOne($option, $this->restaurant);
            $optionModel = Option::create([
                "option_group_id" => $optionGroup->id,
                "product_id" => $product->id,
                "sequence" => $option->sequence,
                "index" => $option->index,
            ]);            
        }
    }

    public function postProcessParents($user_id, $restaurant_id, $broker_id){
        $sql = "UPDATE products AS p
        INNER JOIN (
            SELECT p2.id, p2.name, GROUP_CONCAT(DISTINCT (SELECT p3.name FROM products p3 WHERE p3.id = i2.product_id) SEPARATOR ', ') AS parents 
            FROM products p2
            INNER JOIN options o2 ON p2.id = o2.product_id
            INNER JOIN option_groups og2 ON og2.id = o2.option_group_id
            INNER JOIN items_has_option_groups iog2 ON iog2.option_group_id = og2.id
            INNER JOIN items i2 ON i2.id = iog2.item_id
            WHERE p2.user_id = ? AND p2.broker_id = ? AND p2.restaurant_id = ?
            GROUP BY p2.id
            ORDER BY p2.name
        ) AS p4 ON p.id = p4.id
        SET p.parents = p4.parents
        WHERE p.user_id = ? AND p.broker_id = ? AND p.restaurant_id = ?";
        DB::statement("SET SESSION group_concat_max_len=15000");
        DB::update($sql, [$user_id, $broker_id, $restaurant_id, $user_id, $broker_id, $restaurant_id]);
    }
}
