<div class="h6 modal-title h4" id="contained-modal-title-vcenter">

        <h4 class="mb-1">
            {{$orderSummaryDetail->orderBabelized->shortOrderNumber}} <a href="javascript:;" onclick="$('.order-info').toggle('show')"><i class="fas fa-info-circle"></i></a> 
            <span class="text-muted">
                <small>{{is_object($orderSummaryDetail->broker) ? $orderSummaryDetail->broker->name : ''}}
                {{\Carbon\Carbon::parse($orderSummaryDetail->orderBabelized->createdDate)->format("d/m H:i")}}
                ({{\Carbon\Carbon::parse($orderSummaryDetail->orderBabelized->createdDate)->diffForhumans()}})</small>
                
                @if($orderSummaryDetail->orderBabelized->schedule)
                <small>
                    <i class="fas fa-lg fa-clock text-danger"></i> AGENDADO  
                    <span class="bg-warning">
                        <small>{{date("d/m H:i", strtotime($orderSummaryDetail->orderBabelized->schedule->start))}} ~ {{date("H:i", strtotime($orderSummaryDetail->orderBabelized->schedule->end))}}</small>
                    </span>
                </small>
                @endif                
                
            </span>
        </h4>

    <p class="my-0"><i class="fas fa-user a-fw"></i> {{$orderSummaryDetail->orderBabelized->customerName()}}</p>
    <p class="my-0"><i class="fas fa-map-marker-alt a-fw"></i> {{$orderSummaryDetail->orderBabelized->deliveryFormattedAddress}}</p>
    <p class="my-0"><small>Número de pedidos: {{$orderSummaryDetail->orderBabelized->ordersCountOnMerchant}} - Tipo: {{$orderSummaryDetail->orderBabelized->orderType ?? 'n/a'}}</small></p>
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
    @endif

    <div class="order-info" style="display:none">
        
        @if($orderSummaryDetail->canceled != 1)
            @if(intval($orderSummaryDetail->id) > 0) 
            <livewire:panels.cancellation key="{{ now() }}" :orderSummaryId="$orderSummaryDetail->id" />
            @endif    
        @endif
        
        @if(isset($orderSummaryDetail->orderBabelized->benefits) && is_array($orderSummaryDetail->orderBabelized->benefits))
            <div class="alert alert-info py-1 px-3 mt-1 mb-0">
                @foreach($orderSummaryDetail->orderBabelized->benefits as $benefit)
                    <div class="row">
                        <div class="col-md-12 small">
                            Subsídio: {{$benefit->target}} :: @money($benefit->value)
                            @if($benefit->description != null)<br /><small>{{$benefit->description}}</small> @endif
                        </div>
                        @if(is_array($benefit->sponsorshipValues))
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
                        @endif
                    </div>
                @endforeach

            </div>
        @endif
        @if(isset($orderSummaryDetail->orderBabelized->payments) && is_array($orderSummaryDetail->orderBabelized->payments))
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

</div>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
