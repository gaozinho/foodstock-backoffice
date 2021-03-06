<?php
namespace App\Foodstock\Babel\Interfaces;

interface OrderBabelInterface{

    public function items();
    public function payments();
    public function subtotal();
    public function deliveryFee();
    public function orderAmount();
    
    public function additionalFees();
    public function benefitsTotal();

    public function orderType();
    public function shortOrderNumber();
    public function createdDate();
    public function getFormattedCreatedDate();
    public function ordersCountOnMerchant();
    public function customerName();
    public function deliveryFormattedAddress();
    public function brokerId();
    public function schedule();
    public function benefits();
    public function brokerName();

}