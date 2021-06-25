<?php
   
namespace App\Foodstock\Babel;

class ItemBabel
{
    public $name;
    public $quantity;
    public $unitPrice;
    public $totalPrice;
    public $externalCode;
    public $observations;
    public $subitems = [];

    public function __construct($name, $quantity, $unitPrice, $totalPrice, $externalCode, $observations){
        $this->name = $name; 
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->totalPrice = $totalPrice;
        $this->externalCode = $externalCode;
        $this->observations = $observations;
    }

    public function addSubitem(ItemBabel $item){
        $this->subitems[] = $item;
    }

    public function setSubitems($items){
        $this->subitems = $items;
    }

    public function toArray(){
        return (array) $this;
    }

    public function toJson(){
        return json_decode($this);
    }
}