<div>
    <div class="row justify-content-between align-items-center">
        <div class="col">
            <h2 class="mt-3 mb-0 pb-0">{{ $productionLine->name }} 
                <span class="badge badge-secondary">{{ $total_orders }}</span> 
            </h2>
        </div>
        <div class="col-auto">
                @role('admin')
                    <div class="dropleft loading">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a href="javascript:;" class="dropdown-item" onclick='$(".loading").LoadingOverlay("show", {zIndex : 1050})' wire:click="selectOrders">Selecionar itens</a>
                            <a href="javascript:;" class="dropdown-item" onclick='$(".loading").LoadingOverlay("show", {zIndex : 1050})' style="text-decoration: none" href="javascript:;" wire:click="batchNextStep"><i wire:loading wire:target="batchNextStep" class="fas fa-cog fa-spin"></i> Avançar selecionados</a>
                            <a href="javascript:;" class="dropdown-item" onclick='$(".loading").LoadingOverlay("show", {zIndex : 1050})' style="text-decoration: none" href="javascript:;" wire:click="confirmFinishOrders"><i wire:loading wire:target="confirmFinishOrders" class="fas fa-cog fa-spin"></i> Finalizar selecionados</a>
                        </div>
                    </div>
                @endrole
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
                <span class="badge" style="color: #fff; background-color: #000">Cancelado</span> 
                <span class="badge"><i class="fas fa-lg fa-clock"></i> Pedido agendado</span> 
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-2">
            <livewire:panels.select-restaurant :page="request()->fullUrl()"/>
        </div> 
    </div>      
    <div class="row mt-3">
        @if($selectable)
        <div class="col-12 mb-2">
            <i class="fas fa-check"></i> <a href="javascript:;" onclick="$('.order-checkbox').trigger('click')">Selecionar todos</a>
        </div>    
        @endif
        <div class="col">
            <div class="loading">
                <div>
                    @if($total_orders == 0)
                        <hr />
                        <div class="text-center mt-5">
                            <img src="{{ asset('images/ico-logo.png') }}" class="mt-2 mb-2">
                                <h3>Nenhum pedido nesta etapa.</h3>
                        </div>
                    @endif
                    <div class="row">

                        @foreach ($orderSummariesPreviousStep as $orderSummary)
                            @php
                                $clickAction = "";
                                if($productionLine->next_on_click == 1){
                                    $clickAction = 'wire:click="moveForwardFromCurrentStep(' . $orderSummary->id . ')"';
                                }else{
                                    $clickAction = 'wire:click="orderDetailAndMoveForward(' . $orderSummary->id . ')"';
                                }

                                $babelized = new App\Foodstock\Babel\OrderBabelized($orderSummary->order_json);
                            @endphp
                            @include('livewire.panels.card-include')    
                        @endforeach

                        @foreach ($orderSummaries as $orderSummary)
                            @php
                                $clickAction = "";
                                if($productionLine->next_on_click == 1 && $productionLine->step == $orderSummary->current_step_number){
                                    $clickAction = 'wire:click="orderDetailAndMoveForward(' . $orderSummary->id . ')"';
                                }
                                                                   
                                $cardColor = "";

                                if($orderSummary->canceled == 1){
                                    $cardColor = 'style="background-color: #000"';
                                }elseif($orderSummary->paused == 1){
                                    $cardColor = 'style="background-color: rgb(165, 162, 0)"';
                                }elseif($productionLine->color != '' && isset($stepColors[$orderSummary->current_step_number])){
                                    $cardColor = 'style="background-color: ' . $stepColors[$orderSummary->current_step_number] . '"';
                                }

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

            Livewire.on('loadingData', function(){
                $(".loading").LoadingOverlay("show", {text : "Aguarde", textAnimation : "pulse", textAutoResize : true, textResizeFactor : true, textColor : "#ccc"})
            })
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
@endpush
