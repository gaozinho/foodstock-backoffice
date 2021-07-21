<div class="modal fade order-modal" id="order-modal" tabindex="-1" role="dialog" aria-labelledby="order-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                      @include('livewire.panels.order-header-include')
                </div>
                <div class="modal-body">
                    @if(isset($orderSummaryDetail->orderBabelized->benefits) && count($orderSummaryDetail->orderBabelized->benefits) > 0)
                    <div class="alert alert-info py-1 px-3">
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
                        <div class="alert alert-info py-1 px-3">
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
                    
                    
                    @include('livewire.panels.order-detail-include')
                </div>
            </div>
        </div>
    </div>
        
    <script>
        $(document).ready(function() {
            //moment.locale('pt-br');
            //var time = moment('{{$orderSummaryDetail->orderBabelized->createdDate}}').fromNow();
            //$('#clock').html(time);
        });
    </script>