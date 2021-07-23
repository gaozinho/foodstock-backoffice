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
                            <div class="mb-2 text-center col-xl-3 col-lg-3 col-md-4 col-6">
                                <div {!!$clickAction!!}
                                    onClick='$(".loading").LoadingOverlay("show")'
                                    class="order-card card {{ $productionLine->color == '' ? 'bg-secondary' : '' }}"
                                    {!! $productionLine->color != '' ? 'style="background-color: ' . $stepColors[$orderSummary->current_step_number] . '"' : '' !!}>
                                    <div class="card-body">
                                        <div>
                                            @if($babelized->schedule)
                                            <div class="text-white">
                                                <i class="fas fa-lg fa-clock"></i>
                                                <span>{{date("d/m H:i", strtotime($babelized->schedule->start))}} ~ {{date("H:i", strtotime($babelized->schedule->end))}}</span>
                                            </div>
                                            @endif  
                                            
                                            @if($orderSummary->canceled == 1)
                                            <h4 class="text-white">CANCELADO</h4>
                                            @endif
                                            <h4 class="text-white">{{ str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT) }}</h4>
                                            <div class="m-0 p-0 small text-white">{{ $orderSummary->broker->name }} :: {{$babelized->orderType}} :: {{\Carbon\Carbon::parse($orderSummary->created_at)->diffForhumans()}}</div>
                                        </div>

                                        <div>
                                            @foreach($babelized->items as $item)
                                                <p class="my-0 text-white">{{$item->quantity}} :: {{$item->name}}</p>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>
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

                            @if($productionLine->clickable == 1)
                                <div class="mb-2 text-center col-xl-3 col-lg-3 col-md-4 col-6">
                                    <div wire:click="orderDetail({{$orderSummary->id}}, {{$orderSummary->production_line_id}})"
                                        onClick='$(".loading").LoadingOverlay("show")' 
                                        class="order-card card {{ $productionLine->color == '' ? 'bg-secondary' : '' }}"
                                        {!! $cardColor !!}
                                        >
                                        <div class="card-body">
                                            <div>
                                                @if($babelized->schedule)
                                                <div>
                                                    <i class="fas fa-lg fa-clock"></i>
                                                    <span>{{date("d/m H:i", strtotime($babelized->schedule->start))}} ~ {{date("H:i", strtotime($babelized->schedule->end))}}</span>
                                                </div>
                                                @endif       
                                                @if($orderSummary->canceled == 1)
                                                <h4 class="text-white">CANCELADO</h4>
                                                @endif                                                                                     
                                                <h4 class="text-white">{{ str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT) }}</h4>
                                                <div class="m-0 p-0 small text-white">{{ $orderSummary->broker->name }} :: {{$babelized->orderType}} :: {{\Carbon\Carbon::parse($orderSummary->created_at)->diffForhumans()}}</div>
                                            </div>
                                            <div>
                                                @foreach($babelized->items as $item)
                                                    <p class="my-0 text-white">{{$item->quantity}} :: {{$item->name}}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mb-2 text-center col-xl-3 col-lg-3 col-md-4 col-6">
                                    <div class="card {{ $productionLine->color == '' ? 'bg-secondary' : '' }}" {!! $cardColor !!}>
                                        <div class="card-body text-white">
                                            <div>
                                                @if($babelized->schedule)
                                                <div>
                                                    <i class="fas fa-lg fa-clock"></i>
                                                    <span>{{date("d/m H:i", strtotime($babelized->schedule->start))}} ~ {{date("H:i", strtotime($babelized->schedule->end))}}</span>
                                                </div>
                                                @endif
                                                @if($orderSummary->canceled == 1)
                                                <h4 class="text-white">CANCELADO</h4>
                                                @endif                                                
                                                <span class="h4">{{ str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT) }}</span><br />
                                                <small><span class="text-white">{{ $orderSummary->broker->name }} :: {{$babelized->orderType}} :: {{\Carbon\Carbon::parse($orderSummary->created_at)->diffForhumans()}}</small></span>
                                            </div>
                                            <div>
                                            @foreach($babelized->items as $item)
                                                <p class="my-0">{{$item->quantity}} :: {{$item->name}}</p>
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
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

            Livewire.on('moveForward', function(){
                $(".loading").LoadingOverlay("hide");
            })            

            Livewire.on('closeOrderModal', function(){
                $('#order-modal').modal('hide');
                $('.modal-backdrop').remove();
            })       
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
@endpush
