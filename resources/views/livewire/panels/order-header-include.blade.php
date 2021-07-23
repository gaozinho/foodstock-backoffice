<div class="h6 modal-title h4" id="contained-modal-title-vcenter">
    <p class="my-0">
        {{$orderSummaryDetail->orderBabelized->shortOrderNumber}}
        <span class="small my-0"> :: {{is_object($orderSummaryDetail->broker) ? $orderSummaryDetail->broker->name : ''}} </span> 
        <span class="small">
            <small>{{\Carbon\Carbon::parse($orderSummaryDetail->orderBabelized->createdDate)->format("d/m H:i")}}</small>
            <small id="clock" class="text-muted">({{\Carbon\Carbon::parse($orderSummaryDetail->created_at)->diffForhumans()}})</small>
        </span>
    </p>
    <p class="my-0">{{$orderSummaryDetail->orderBabelized->customerName()}}</p>
    <div>
        <p class="small my-0">Endereço: {{$orderSummaryDetail->orderBabelized->deliveryFormattedAddress}}</p>
    </div>
    <p class="small my-0">Número de pedidos: {{$orderSummaryDetail->orderBabelized->ordersCountOnMerchant}} - Tipo: {{$orderSummaryDetail->orderBabelized->orderType ?? 'n/a'}}</p>
    @if($orderSummaryDetail->canceled == 1)
        @foreach($orderSummaryDetail->cancellationReasons()->get() as $cancellation)
        <div class="alert alert-danger mt-3 mb-0 mx-0">
            <div class="small">
                <b>ATENÇÃO! Pedido CANCELADO</b><br />
                <small>Solicitante: {{$cancellation->origin ?? "N/A"}}
                    <br />Motivo: {{$cancellation->reason ?? "N/A"}} ({{$cancellation->code ?? "N/A"}})</small>
            </div>     
        </div>
        @endforeach
    @else
        @if(intval($orderSummaryDetail->id) > 0) 
        <livewire:panels.cancellation key="{{ now() }}" :orderSummaryId="$orderSummaryDetail->id" />
        @endif    
    @endif
    
    @if(isset($orderSummaryDetail->orderBabelized->benefits) && count($orderSummaryDetail->orderBabelized->benefits) > 0)
        <div class="alert alert-info py-1 px-3 mt-1 mb-0">
            @foreach($orderSummaryDetail->orderBabelized->benefits as $benefit)
                <div class="row">
                    <div class="col-md-12 small">
                        Subsídio: {{$benefit->target}} :: @money($benefit->value)
                        @if($benefit->description != null)<br /><small>{{$benefit->description}}</small> @endif
                    </div>
                    <div class="col-md-12 small">
                        <ul class="mb-0">
                        @foreach($benefit->sponsorshipValues as $sponsorshipValue)
                            <li>
                                {{$sponsorshipValue->name}} :: @money($sponsorshipValue->value)
                                @if($sponsorshipValue->description != null) <br /><small>{{$sponsorshipValue->description}}</small> @endif
                            </li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach

        </div>
    @endif
    @if(isset($orderSummaryDetail->orderBabelized->payments))
        <div class="alert alert-info py-1 px-3 mt-1 mb-0">
            <div class="row">
                <div class="col-md-12 small">
                    Valor a receber: @money($orderSummaryDetail->orderBabelized->payments->pending) :: 
                    Valor pago antecipadamente: @money($orderSummaryDetail->orderBabelized->payments->prepaid)
                </div>
                <div class="col-md-12 small">
                    <ul class="mb-0">
                    @foreach($orderSummaryDetail->orderBabelized->payments->methods as $method)
                        <li>
                            @money($method->value) pago em {{$method->method}}
                            @if($method->card_brand != null) bandeira {{$method->card_brand ?? 'n/a'}} @endif
                            @if(intval($method->cash_changeFor) > 0) - Levar troco para: @money($method->cash_changeFor) @endif
                            @if($method->wallet_name != null) - Wallet: {{$method->wallet_name ?? 'n/a'}} @endif
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

</div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
