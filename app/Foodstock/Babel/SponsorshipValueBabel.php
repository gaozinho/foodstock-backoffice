<?php
   
namespace App\Foodstock\Babel;

class SponsorshipValueBabel
{

    public $description;
    public $value;
    public $name;

    public function __construct($description, $name, $value){
        $this->description = $description;
        $this->name = $name;
        $this->value = $value;
    }   

    public function toArray(){
        return (array) $this;
    }

    public function toJson(){
        return json_decode($this);
    }
}