<?php
   
namespace App\Foodstock\Babel;

use App\Foodstock\Babel\SponsorshipValueBabel;

class BenefitBabel
{

    public $targetId;
    public $sponsorshipValues;
    public $description;
    public $value;
    public $target;

    public function __construct($targetId, $description, $value, $target){
        $this->targetId = $targetId;
        $this->description = $description;
        $this->value = $value;
        $this->target = $target;
    }

    public function addSponsorshipValues(SponsorshipValueBabel $sponsorshipValue){
        $this->sponsorshipValues[] = $sponsorshipValue;
    }

    public function setMethod($sponsorshipValues){
        $this->sponsorshipValues = $sponsorshipValues;
    }    

    public function toArray(){
        return (array) $this;
    }

    public function toJson(){
        return json_decode($this);
    }
}