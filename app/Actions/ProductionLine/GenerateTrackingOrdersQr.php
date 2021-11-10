<?php

namespace App\Actions\ProductionLine;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Hashids\Hashids;

class GenerateTrackingOrdersQr
{

    public function generate($url){
        return QrCode::size(100)->generate($url);
    }

    public function generatePng($url, $size = 100){
        return QrCode::format('png')->size($size)->generate($url);
    }

    public function encode($string){
        $hashids = new Hashids('', 10);
        return $hashids->encode($string); 
    }

    public function decode($string){
        $hashids = new Hashids('', 10);
        $decoded = $hashids->decode($string); 
        if(count($decoded) > 0) return $decoded[0];
    }    

}
