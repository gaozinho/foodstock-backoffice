<div>
    <div class="row justify-content-between align-items-center">
        <div class="col">
            <h2 class="mt-3 mb-0 pb-0">{{ $productionLine->name }} 
                <span class="badge badge-secondary">{{ $total_orders }}</span> 
            </h2>
        </div>
        <div class="col-auto">
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
                <span class="badge" style="color: #fff; background-color: #ff8e09">Cancelado</span> 
                

                <span class="badge"><i class="fas fa-lg fa-clock"></i> Pedido agendado</span> 
                
            </span>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="loading">
                <div>

                    @if($total_orders == 0)
                        <div class="text-center mt-5">
                            <img src="{{ asset('images/ico-logo.png') }}" class="mt-2 mb-2">
                                <h3>Nenhum item pendente.</h3>
                        </div>
                    @endif

                    <div class="row">
                        @foreach ($orderSummariesPreviousStep as $orderSummary)
                            @php
                                $babelized = new App\Foodstock\Babel\OrderBabelized($orderSummary->order_json);
                            @endphp

                            @php
                                $clickAction = "";
                                if($productionLine->clickable == 1 && $productionLine->next_on_click == 1){
                                    $clickAction = 'wire:click="moveForwardFromCurrentStep(' . $orderSummary->id . ')"';
                                }else if($productionLine->clickable == 1){
                                    $clickAction = 'wire:click="orderDetailAndMoveForward(' . $orderSummary->id . ')"';
                                }
                            @endphp
                            
                            @include('livewire.panels.card-include')    
                        @endforeach

                        @foreach ($orderSummaries as $orderSummary)
                            @php
                                $clickAction = "";
                                $cardColor = "";

                                if($orderSummary->canceled == 1){
                                    $cardColor = 'style="background-color: #ff8e09"';
                                }elseif($orderSummary->paused == 1){
                                    $cardColor = 'style="background-color: rgb(165, 162, 0)"';
                                }elseif($productionLine->color != '' && isset($stepColors[$orderSummary->current_step_number])){
                                    $cardColor = 'style="background-color: ' . $stepColors[$orderSummary->current_step_number] . '"';
                                }
                            @endphp
                            @php
                                $babelized = new App\Foodstock\Babel\OrderBabelized($orderSummary->order_json);
                            @endphp                            
                            
                            @include('livewire.panels.card-include', ["cardColor" => $cardColor])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(is_object($orderSummaryDetail) && is_object($orderSummaryDetail->orderBabelized))
        @include('livewire.panels.order-modal-include')
    @endif

</div>

@push('scripts')
    <script>
        function reloadPage(){
            return setInterval(() => { 
                Livewire.emit('loadData');
             }, 30000);
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

            Livewire.on('moveForward', function(){
                $(".loading").LoadingOverlay("hide");
            })            

            Livewire.on('closeOrderModal', function(){
                $('#order-modal').modal('hide');
                $('.modal-backdrop').remove();
                reloadDataInterval = reloadPage();
            })       
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
@endpush
