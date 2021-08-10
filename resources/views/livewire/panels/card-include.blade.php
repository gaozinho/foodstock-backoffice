<div class="mb-2 col-xl-3 col-lg-3 col-md-4 col-6">
    <div class="card-painel card-margin" 
        @if($productionLine->clickable == 1)
            wire:click="orderDetail({{$orderSummary->id}}, {{$orderSummary->production_line_id}})"
            onClick='$(".loading").LoadingOverlay("show")'
        @endif
    >

        <div class="card-body">
            <div class="order-card">
                <div class="order-card-title-wrapper">
                    <div class="order-card-date-primary" {!!$cardColor ?? 'style="background-color: ' . $stepColors[$orderSummary->current_step_number] . '"' !!}>
                        <span class="order-card-date-day text-white">{{ str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT) }}</span>
                        
                    </div>
                    <div class="order-card-meeting-info">
                        <span class="order-card-pro-title">IFOOD <span class="text-muted"><small>{{$babelized->orderType}}</small></span>
                            @if($babelized->schedule)
                                <i class="fas fa-lg fa-clock text-danger"></i>
                            @endif
                        </span>
                        
                        @if($orderSummary->canceled == 1)
                            <span class="order-card-meeting-time text-danger">CANCELADO</span>
                        @else
                            <span class="order-card-meeting-time">{{\Carbon\Carbon::parse($orderSummary->created_at)->diffForhumans()}}</span>
                        @endif
                        @if($babelized->schedule)
                            <span class="order-card-meeting-time">
                                <small>{{date("d/m H:i", strtotime($babelized->schedule->start))}} ~ {{date("H:i", strtotime($babelized->schedule->end))}}</small>
                            </span>
                        @endif
                    </div>
                </div>
                <ol class="order-card-meeting-points mb-0">
                    @foreach($babelized->items as $item)
                        <li seq="{{$item->quantity}}" class="order-card-meeting-item"><span>{{$item->name}}</span></li>
                    @endforeach                                                    
                    
                </ol>
            </div>
        </div>
    </div>
</div>