<div class="h6 modal-title h4" id="contained-modal-title-vcenter">
    <p class="my-0">
        {{$orderSummaryDetail->orderBabelized->shortOrderNumber}}
        <span class="small my-0"> :: IFOOD </span> 
        <span class="small">
            <small>{{\Carbon\Carbon::parse($orderSummaryDetail->orderBabelized->createdDate)->format("d/m H:i")}}</small>
            <small id="clock" class="text-muted">({{\Carbon\Carbon::parse($orderSummaryDetail->created_at)->diffForhumans()}})</small>
        </span>
    </p>
    <p class="my-0">{{$orderSummaryDetail->orderBabelized->customerName()}}</p>
    <div>
        <p class="small my-0">Endereço: {{$orderSummaryDetail->orderBabelized->deliveryFormattedAddress}}</p>
    </div>
    <p class="small my-0">Número de pedidos: {{$orderSummaryDetail->orderBabelized->ordersCountOnMerchant}}</p>
    
</div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
