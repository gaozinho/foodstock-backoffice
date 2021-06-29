<div class="h6 modal-title h4" id="contained-modal-title-vcenter">
    <p class="my-0">{{$orderSummaryDetail->orderBabelized->shortOrderNumber}}<span class="small my-0"> :: IFOOD </span> <span class="small"><time
                datetime="1623940830000">{{$orderSummaryDetail->orderBabelized->getFormattedCreatedDate()}}</time> (<time
                datetime="1623940830000">18:13</time>)</span></p>
    <p class="my-0">{{$orderSummaryDetail->orderBabelized->customerName()}}</p>
    <div>
        <p class="small my-0">Endereço: {{$orderSummaryDetail->orderBabelized->deliveryFormattedAddress}}</p>
    </div>
    <p class="small my-0">Número de pedidos: {{$orderSummaryDetail->orderBabelized->ordersCountOnMerchant}}</p>
</div><button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>