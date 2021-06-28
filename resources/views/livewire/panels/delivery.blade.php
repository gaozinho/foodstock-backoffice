<div>
    <div class="full-screen mb-3">
        <div class="d-flex justify-content-between align-items-end">
            <div>
                <h2 class="mt-3 mb-0 pb-0">Painel de delivery
                    <span class="badge badge-secondary">{{ $total_orders }}</span>
                </h2>
                <span class="legend mt-0 pt-0">Legenda:
                    <span class="badge bg-danger p-1">Produzindo</span> 
                    <span class="badge bg-success p-1">Pronto para retirar</span>
                </span>                
            </div>
            <div>
                <button class="btn btn-sm btn-primary" id="bt-fullscreen"><i class="fas fa-expand-arrows-alt"></i> Modo painel <small>(F4)</small></button>
            </div>
       </div>

        
    </div>

    <div class="card loading">
        <div class="card-body">
            @if($total_orders == 0)
            <div class="text-center">
                <img src="{{ asset('images/ico-logo.png') }}" class="mt-2 mb-2">
                    <h3>Nenhum item em produção ou pronto.</h3>
            </div>
            @endif
            <div class="row">
                @php
                    $prevStartNumber = -1;                        
                @endphp                        
                @foreach ($orderSummaries as $index => $orderSummary)
                    @php
                        $clickAction = 'wire:click="orderDetail(' . $orderSummary->id . ', ' . $orderSummary->production_line_id . ')"';
                        $curStartNumber = intval(substr($orderSummary->friendly_number, 0, 1));
                        if($curStartNumber != $prevStartNumber){
                            if($prevStartNumber >= 0) echo '</div>';
                            $prevStartNumber = $curStartNumber;
                            //Abre coluna
                            if($index < count($orderSummaries)) echo '<div class="text-center col">';
                        }
                    @endphp
                        <div {!!$clickAction!!}
                            onClick='$(".loading").LoadingOverlay("show")'
                            class="order-card card mb-2 {{$lastStepProductionLine->id == $orderSummary->production_line_id ? 'bg-success' : 'bg-danger'}}">
                            <div class="card-body">
                                <h4 class="text-white">{{ $orderSummary->friendly_number }}</h4>
                                <div class="m-0 p-0 small text-white">{{ $orderSummary->broker->name }}</div>
                            </div>
                        </div>
                @endforeach
            </div>
        </div>
    </div>


    @if(is_object($orderSummaryDetail) && is_object($orderSummaryDetail->orderBabelized))
    <div class="modal fade order-modal" id="order-modal" tabindex="-1" role="dialog" aria-labelledby="order-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
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
                </div>
                <div class="modal-body">
                    <div data-testid="wrapper" class="_loading_overlay_wrapper css-79elbk">
                        <div class="small">
                            <div class="row pb-2">
                                <div class="col-2 col-lg-1 col-sm-1 small font-weight-bold">Qtde</div>
                                <div class="col-7 col-lg-8 col-sm-8 small font-weight-bold">Item</div>
                                <div class="col-3 col-lg-3 small text-right font-weight-bold">Total</div>
                            </div>
                            @foreach($orderSummaryDetail->orderBabelized->items as $item)
                            <div>
                                <div class="row pb-2">
                                    <div class="col-2 col-lg-1 col-sm-1">
                                        <h6 class="m-0">{{$item->quantity}}</h6>
                                    </div>
                                    <div class="col-7 col-lg-8 col-sm-8 small">{{$item->name}} 
                                        @if($item->observations) <span class="px-1 bg-warning text-dark">{{$item->observations}}</span> @endif
                                    </div>
                                    <div class="col-3 col-lg-3 small text-right">@money($item->totalPrice)</div>
                                    @foreach($item->subitems as $subitem)
                                    <div class="col-12 small">
                                        <div>
                                            <div class="row">
                                                <div class="col-7 offset-2">{{$subitem->quantity}} {{$subitem->name}} 
                                                    @if($subitem->observations) <span class="px-1 bg-warning text-dark">{{$subitem->observations}}</span> @endif
                                                </div>
                                                <div class="col-3 text-right">@money($subitem->totalPrice)</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                            <hr class="my-2">
                            <div class="row pb-0">
                                <div class="col-9 small">
                                    <div>Subtotal</div>
                                </div>
                                <div class="col-3 small text-right">@money($orderSummaryDetail->orderBabelized->subtotal)</div>
                            </div>
                            <div class="row pb-0">
                                <div class="col-9 small">Entrega</div>
                                <div class="col-3 small text-right">@money($orderSummaryDetail->orderBabelized->deliveryFee)</div>
                            </div>
                            <div class="row pb-0">
                                <div class="col-9 small">Total</div>
                                <div class="col-3 small text-right">@money($orderSummaryDetail->orderBabelized->orderAmount)</div>
                            </div>
                        </div><span></span>
                        @if($lastStepProductionLine->id == $orderSummaryDetail->production_line_id)
                        <div>
                            <div role="toolbar" class="btn-toolbar"><button type="button" name="finishProcess" value="finishProcess" wire:click="finishProcess({{$orderSummaryDetail->id}})" class="mt-2 btn btn-success btn-block"><i wire:loading wire:target="finishProcess" class="fas fa-cog fa-spin"></i> Despachar</button></div>
                        </div>
                        @endif
                        <div>
                            <div role="toolbar" class="btn-toolbar"><button type="button" data-dismiss="modal" class="mt-2 btn btn-secondary btn-block">Voltar</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


</div>
@push('scripts')
    <script>

        function reloadPage(){
            return setInterval(() => { 
                Livewire.emit('loadData');
             }, 60000);
        }

        function fullScreen(){
            $(".full-screen").hide();
            $("#main-container").attr("fullscreen" , "1").addClass("container-full").removeClass("container").removeClass("my-5");
            Swal.fire({
                text: 'Aperte ESC para sair do modo painel.',
                timer: 3000,
                showCancelButton: false,
                showCloseButton: false
            });
        }

        function cancelFullScreen(){
            $(".full-screen").show();
            $("#main-container").removeAttr("fullscreen").removeClass("container-full").addClass("container").addClass("my-5");
        }        

        $(document).ready(function() {

            $(document).keyup(function(e) {

                if (e.key === "Escape") { // escape key maps to keycode `27`
                    cancelFullScreen();
                }else if (e.key === "F4") { // escape key maps to keycode `27`
                    fullScreen();
                }
           });
            
            Livewire.hook('element.updated', (el, component) => {
                if($("#main-container").attr("fullscreen") == "1"){
                    fullScreen();
                }
            })

            $("#bt-fullscreen").on("click", function(){
                fullScreen();
            });

            var reloadDataInterval = reloadPage();

            $('#order-modal').on('hide.bs.modal', function (e) {
                reloadDataInterval = reloadPage();
            });

            $('#order-modal').on('show.bs.modal', function (e) {
                clearInterval(reloadDataInterval);
            });            

            Livewire.on('openOrderModal', function(){
                clearInterval(reloadDataInterval);
                $(".loading").LoadingOverlay("hide");
                $('#order-modal').modal();
            })

            Livewire.on('closeOrderModal', function(){
                $('#order-modal').modal('hide');
                $('.modal-backdrop').remove();
            })       
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
@endpush
