
<div class="mb-2 col-xl-3 col-lg-3 col-md-4 col-6">
    <div class="card-painel card-margin" 
        @if($clickAction != "")
            {!! $clickAction !!} 
        @elseif($productionLine->clickable == 1)
            wire:click="orderDetail({{$orderSummary->id}}, {{$orderSummary->production_line_id}})"
        @endif
        onClick='$(".loading").LoadingOverlay("show")'
    >

        <div class="card-body py-3 px-3">
            <div class="order-card">
                <h1><span class="badge badge-secondary w-100">{{ str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT) }}</span></h1>
                <div style="line-height: 1" class="row">
                    <div class="col-sm-12">
                        <small>
                            @if($orderSummary->canceled == 1)
                                <span class="text-danger">CANCELADO</span>
                            @else
                                {{\Carbon\Carbon::parse($orderSummary->created_at)->diffForhumans()}}
                            @endif

                        
                        
                        {!!$babelized->brokerName() ? $babelized->brokerName() . ' &bull;' : ''!!} {{$orderSummary->restaurant}} <!-- {{$babelized->orderType}} --></small>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <small>
                            @if($babelized->schedule)
                                
                                <small><i class="fas fa-lg fa-clock text-danger"></i> {{date("d/m H:i", strtotime($babelized->schedule->start))}} ~ {{date("H:i", strtotime($babelized->schedule->end))}}</small>
                            @endif
                        </small>
                    </div>
                    @if($productionLine->clickable == 0)
                        <div class="col-sm-12">
                            <hr class="my-2" />
                            
                                @foreach($babelized->items as $item)
                                    <div>
                                        <small>{{ $item->quantity }} - {{$item->name}}</small>
                                        @foreach ($item->subitems as $subitem)
                                            <div class="ml-3">
                                                <div>
                                                    <small>
                                                        {{ $subitem->quantity }} {{ $subitem->name }}
                                                        @if ($subitem->observations) 
                                                            <span class="bg-warning">{{ $subitem->observations }}</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        @endforeach                                
                                    </div>
                                @endforeach
                        </div>
                    @endif

                </div>                
            </div>
        </div>
    </div>
</div>