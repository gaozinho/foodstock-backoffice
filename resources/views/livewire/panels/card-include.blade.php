<div class="mb-2 col-xl-3 col-lg-3 col-md-4 col-6">
    <div class="card-painel card-margin">
        @if($selectable)
        <div class="card-header pl-2 pt-2 pb-0">
            <div class="form-check">
                <input type="checkbox" id="order{{ $orderSummary->order_id }}" value="{{ $orderSummary->order_id }}" wire:model.defer="selectedOrderIds"  class="form-check-input order-checkbox">
                <label class="form-check-label" for="order{{ $orderSummary->order_id }}">
                    Selecinar
                </label>
            </div>
        </div>
        @endif
        <div class="card-body py-3 px-3"
            @if($clickAction != "")
                {!! $clickAction !!} 
            @else
                wire:click="orderDetail({{$orderSummary->id}}, {{$orderSummary->production_line_id}})"
            @endif
            onClick='$(".loading").LoadingOverlay("show")'        
        >
            <div class="order-card">
                <h1><span class="badge badge-secondary w-100" {!!$cardColor ?? 'style="background-color: ' . $stepColors[$orderSummary->current_step_number] . '"' !!}>{{ @friendlyNumber($orderSummary->friendly_number) }}</span></h1>
                <div style="line-height: 1" class="row">
                    <div class="col-sm-12">
                        <small>
                            @if($orderSummary->canceled == 1)
                                <span class="text-danger">CANCELADO</span>
                            @else
                                {{\Carbon\Carbon::parse($babelized->createdDate)->diffForhumans()}}
                            @endif
                            {!!$babelized->brokerName() ? $babelized->brokerName() . ' &bull;' : ''!!} {{$orderSummary->restaurant}}
                            @if($orderSummary->paused == 1 && intval($orderSummary->paused_by) > 0)
                               @php
                                    $name = "Indefinido";
                                    $user = \App\Models\User::find($orderSummary->user_id);
                                    if(is_object($user)){
                                        $name = $user->name;
                                    }
                                @endphp
                                &bull; Pausado por {{$name}}   
                             @endif
                        </small>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <small>
                            @if($babelized->schedule)
                                <small><i class="fas fa-lg fa-clock text-danger"></i> {{date("d/m H:i", strtotime($babelized->schedule->start))}} ~ {{date("H:i", strtotime($babelized->schedule->end))}}</small>
                            @endif
                        </small>
                    </div>
                    @if($productionLine->clickable == 1)
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