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
}
