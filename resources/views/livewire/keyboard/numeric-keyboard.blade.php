<div>
    <div class="full-screen mb-3">
        <h2 class="mt-3 mb-0 pb-0">Encontre um pedido</h2>
        <p class="my-0">Digite o número do pedido para localizá-lo na linha de produção.</p>
    </div>

    <div class="card loading">
        <div class="card-body">
            <div>
                <div class="row mt-3">
                    <div class="col">
                        <div class="form-group"><input name="orderNumber" readonly=""
                                class="text-center form-control form-control-lg mb-2" id="orderNumber" value=""></div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col"><button value="1" type="button"
                            class="btn btn-lg btn-block btn-outline-secondary py-3">1</button></div>
                    <div class="col"><button value="2" type="button"
                            class="btn btn-lg btn-block btn-outline-secondary py-3">2</button></div>
                    <div class="col"><button value="3" type="button"
                            class="btn btn-lg btn-block btn-outline-secondary py-3">3</button></div>
                </div>
                <div class="row mb-2">
                    <div class="col"><button value="4" type="button"
                            class="btn btn-lg btn-block btn-outline-secondary py-3">4</button></div>
                    <div class="col"><button value="5" type="button"
                            class="btn btn-lg btn-block btn-outline-secondary py-3">5</button></div>
                    <div class="col"><button value="6" type="button"
                            class="btn btn-lg btn-block btn-outline-secondary py-3">6</button></div>
                </div>
                <div class="row mb-2">
                    <div class="col"><button value="7" type="button"
                            class="btn btn-lg btn-block btn-outline-secondary py-3">7</button></div>
                    <div class="col"><button value="8" type="button"
                            class="btn btn-lg btn-block btn-outline-secondary py-3">8</button></div>
                    <div class="col"><button value="9" type="button"
                            class="btn btn-lg btn-block btn-outline-secondary py-3">9</button></div>
                </div>
                <div class="row mb-2">
                    <div class="col"></div>
                    <div class="col"><button value="0" type="button"
                            class="btn btn-lg btn-block btn-outline-secondary py-3">0</button></div>
                    <div class="col"><button value="Backspace" type="button" class="btn btn-lg btn-block btn-secondary py-3"><i class="fas fa-backspace"></i></button></div>
                </div>
            </div>
        </div>
    </div>

    @if(is_object($orderSummaryDetail) && is_object($orderSummaryDetail->orderBabelized))
    <div class="modal fade order-modal" id="order-modal" tabindex="-1" role="dialog" aria-labelledby="order-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    @include('livewire.panels.order-header-include')
                </div>
                <div class="modal-body">
                    <div data-testid="wrapper" class="_loading_overlay_wrapper css-79elbk">
                        
                        @include('livewire.panels.order-detail-include')
                        <hr class="my-2">
                        <div>
                            <div role="toolbar" class="btn-toolbar"><span class="h3">Etapa: {{ $productionLine->name }}</span></div>
                        </div>

                        <div>
                            <div role="toolbar" class="btn-toolbar"><button type="button" data-dismiss="modal" class="mt-2 btn btn-secondary btn-block">Fechar</button></div>
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

        function addToDisplay(clickedValue){
            var orderPanel = $("#orderNumber");
            if($.isNumeric(clickedValue)){
                var displayedVal = orderPanel.val();
                orderPanel.val(displayedVal + clickedValue);
                if(orderPanel.val().length == 4){
                    loadOrderData(orderPanel.val());
                    orderPanel.val("");
                }
            }else if(clickedValue == "Backspace"){
                orderPanel.val("");
            }
        }

        function loadOrderData(order_number){
            $(".loading").LoadingOverlay("show");
            Livewire.emit('orderDetail', {order_number : order_number});
        }

        $(document).ready(function() {
            $(".btn").on("click", function(){
                var clickedValue = $(this).val();
                addToDisplay(clickedValue);
            });

            $(document).keyup(function(e) {
                addToDisplay(e.key);
           });      
           
           Livewire.on('openOrderModal', function(){
                $(".loading").LoadingOverlay("hide");
                $('#order-modal').modal();
            })          
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

@endpush
