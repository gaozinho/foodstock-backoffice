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

                                $cardColor = "";
                                if($orderSummary->paused == 1){
                                    $cardColor = 'style="background-color: rgb(165, 162, 0)"';
                                }elseif($productionLine->color != '' && isset($stepColors[$orderSummary->current_step_number])){
                                    $cardColor = 'style="background-color: ' . $stepColors[$orderSummary->current_step_number] . '"';
                                }

                                
                            @endphp

                            <div class="mb-2 text-center col-xl-2 col-lg-2 col-md-4 col-6">

                                @if($productionLine->clickable == 1)
                                    <div wire:click="orderDetail({{$orderSummary->id}}, {{$orderSummary->production_line_id}})"
                                        onClick='$(".loading").LoadingOverlay("show")' 
                                        class="order-card card {{ $productionLine->color == '' ? 'bg-secondary' : '' }}"
                                        {!! $cardColor !!}
                                        >
                                        <div class="card-body">
                                            <h4 class="text-white">{{ str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT) }}</h4>
                                            <div class="m-0 p-0 small text-white">{{ $orderSummary->broker->name }}</div>
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $babelized = new App\Foodstock\Babel\OrderBabelized($orderSummary->order_json);
                                    @endphp
                                    <div class="card {{ $productionLine->color == '' ? 'bg-secondary' : '' }}" {!! $cardColor !!}>
                                        <div class="card-body text-white">
                                            <div>
                                                <span class="h4">{{ str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT) }}</span> <span class="text-white">({{ $orderSummary->broker->name }})</span>
                                            </div>
                                            <div>
                                            @foreach($babelized->items as $item)
                                                <p class="my-0">{{$item->quantity}} :: {{$item->name}}</p>
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
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

                        <div>
                            <div role="toolbar" class="btn-toolbar"><button type="button" name="nextStep" value="nextStep" wire:click="nextStep({{$orderSummaryDetail->id}})" class="mt-2 btn btn-success btn-block"><i wire:loading wire:target="nextStep" class="fas fa-cog fa-spin"></i> {{ $productionLine->name }} OK</button></div>
                        </div>
                        @if($productionLine->can_pause && $orderSummaryDetail->paused != 1)
                        <div>
                            <div role="toolbar" class="btn-toolbar"><button type="button" name="pause" value="pause" wire:click="pause({{$orderSummaryDetail->id}})" class="mt-2 btn btn-warning btn-block"><i wire:loading wire:target="pause" class="fas fa-cog fa-spin"></i> Pausar</button></div>
                        </div>
                        @endif
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

            Livewire.on('closeOrderModal', function(){
                $('#order-modal').modal('hide');
                $('.modal-backdrop').remove();
            })       
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
@endpush
