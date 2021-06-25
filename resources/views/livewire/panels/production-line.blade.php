<div>
    <div>
        <h2 class="mt-3 mb-0 pb-0">{{ $productionLine->name }} 
            <span class="badge badge-secondary">{{ $total_orders }}</span> 
        </h2>
        <span class="legend mt-0 pt-0">Legenda:
            @foreach ($legends as $legend)
                @php
                    $startText = "";
                    if($legend['order'] == "previous") $startText = "Passou na ";
                    else if($legend['order'] == "current") $startText = "Aguardando ";
                @endphp
                <span class="badge" style="color: #fff; background-color: {{$legend['color']}}">{{$startText}}{{$legend['name']}}</span> 
            @endforeach
            @if($productionLine->can_pause)
                <span class="badge" style="color: #fff; background-color: rgb(165, 162, 0)">Pausado</span> 
            @endif
        </span>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="card loading">
                <div class="card-body">

                    @if($total_orders == 0)
                    <div class="text-center">
                        <img src="{{ asset('images/ico-logo.png') }}" class="mt-2 mb-2">
                            <h3>Nenhum item pendente.</h3>
                    </div>
                    @endif


                    <div class="row">
                        @foreach ($orderSummariesPreviousStep as $orderSummary)
                            @php
                                $clickAction = "";
                                if($productionLine->clickable == 1){
                                    $clickAction = 'wire:click="orderDetailAndMoveForward(' . $orderSummary->id . ')"';
                                }
                            @endphp
                            <div class="mb-2 text-center col-xl-2 col-lg-2 col-md-4 col-6">
                                <div {!!$clickAction!!}
                                    onClick='$(".loading").LoadingOverlay("show")'
                                    class="order-card card {{ $productionLine->color == '' ? 'bg-secondary' : '' }}"
                                    {!! $productionLine->color != '' ? 'style="background-color: ' . $stepColors[$orderSummary->current_step_number] . '"' : '' !!}>
                                    <div class="card-body">
                                        <h4 class="text-white">{{ $orderSummary->friendly_number }}</h4>
                                        <div class="m-0 p-0 small text-white">{{ $orderSummary->broker->name }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @foreach ($orderSummaries as $orderSummary)
                            @php
                                $clickAction = "";
                                if($productionLine->clickable == 1){
                                    $clickAction = 'wire:click="orderDetail(' . $orderSummary->id . ', ' . $orderSummary->production_line_id . ')"';
                                }
                            @endphp                
                            <div class="mb-2 text-center col-xl-2 col-lg-2 col-md-4 col-6">
                                <div {!!$clickAction!!}
                                    onClick='$(".loading").LoadingOverlay("show")'
                                    class="order-card card {{ $productionLine->color == '' ? 'bg-secondary' : '' }}"
                                    {!! $productionLine->color != '' ? 'style="background-color: ' . (isset($stepColors[$orderSummary->current_step_number]) ? $stepColors[$orderSummary->current_step_number] : "") . '"' : '' !!}>
                                    <div class="card-body">
                                        <h4 class="text-white">{{ $orderSummary->friendly_number }}</h4>
                                        <div class="m-0 p-0 small text-white">{{ $orderSummary->broker->name }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade order-modal" id="order-modal" tabindex="-1" role="dialog" aria-labelledby="order-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="h6 modal-title h4" id="contained-modal-title-vcenter">
                        <p class="my-0">7208<span class="small my-0"> :: IFOOD </span> <span class="small"><time
                                    datetime="1623940830000">17/06 11:40:30</time> (<time
                                    datetime="1623940830000">18:13</time>)</span></p>
                        <p class="my-0">Celeno Jésus Viana</p>
                        <div>
                            <p class="small my-0">Endereço: Rua dos Timbiras, Bairro: Lourdes</p>
                            <p class="small my-0">Entre Avenida Bias Fortes e Rua São Paulo</p>
                        </div>
                        <p class="small my-0">Número de pedidos: 15</p>
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
                            <div>
                                <div class="row pb-2">
                                    <div class="col-2 col-lg-1 col-sm-1">
                                        <h6 class="m-0">2</h6>
                                    </div>
                                    <div class="col-7 col-lg-8 col-sm-8 small">For You ••• Macarrão à Bolonhesa + Bebida
                                    </div>
                                    <div class="col-3 col-lg-3 small text-right">59,98</div>
                                    <div class="col-12 small">
                                        <div>
                                            <div class="row">
                                                <div class="col-7 offset-2">2 Suco Campo Largo Uva - 250ml</div>
                                                <div class="col-3 text-right">2,99</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-7 offset-2">2 Sim, preciso de talher!</div>
                                                <div class="col-3 text-right">1,00</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row pb-0">
                                <div class="col-9 small">
                                    <div>Subtotal</div>
                                </div>
                                <div class="col-3 small text-right">67,96</div>
                            </div>
                            <div class="row pb-0">
                                <div class="col-9 small">Pago online através do aplicativo</div>
                                <div class="col-3 small text-right">-67,96</div>
                            </div>
                            <div class="row pb-0">
                                <div class="col-9 small">Desconto do iFood</div>
                                <div class="col-3 small text-right">-4,99</div>
                            </div>
                        </div><span></span>
                        <div>
                            <div role="toolbar" class="btn-toolbar"><button type="button" name="nextStep" value="nextStep" wire:click="nextStep({{$orderSummaryDetail->id}})" class="mt-2 btn btn-success btn-block"><i wire:loading wire:target="nextStep" class="fas fa-cog fa-spin"></i> {{ $productionLine->name }} OK</button></div>
                        </div>
                        @if($productionLine->can_pause)
                        <div>
                            <div role="toolbar" class="btn-toolbar"><button type="button" name="pause" value="pause" wire:click="pause({{$orderSummaryDetail->id}})" class="mt-2 btn btn-warning btn-block"><i wire:loading wire:target="pause" class="fas fa-cog fa-spin"></i> Pausar</button></div>
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


</div>
@push('scripts')
    <script>

        function reloadPage(){
            return setInterval(() => { 
                Livewire.emit('loadData');
             }, 60000);
        }

        $(document).ready(function() {
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

            Livewire.on('closeOrderModal', postId => {
                $('#order-modal').modal('hide');
            })       
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
@endpush
