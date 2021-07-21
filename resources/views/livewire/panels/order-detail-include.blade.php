<div>
    <div class="small">
        <div class="row pb-2">
            <div class="col-2 col-lg-1 col-sm-1 small font-weight-bold">Qtde</div>
            <div class="col-7 col-lg-8 col-sm-8 small font-weight-bold">Item</div>
            <div class="col-3 col-lg-3 small text-right font-weight-bold">Total</div>
        </div>
        @foreach ($orderSummaryDetail->orderBabelized->items as $item)
            <div>
                <div class="row pb-2">
                    <div class="col-2 col-lg-1 col-sm-1">
                        <h6 class="m-0">{{ $item->quantity }}</h6>
                    </div>
                    <div class="col-7 col-lg-8 col-sm-8 small">{{ $item->name }}
                        @if ($item->observations) <span
                                class="px-1 bg-warning text-dark">{{ $item->observations }}</span> @endif
                    </div>
                    <div class="col-3 col-lg-3 small text-right">@money($item->totalPrice)</div>
                    @foreach ($item->subitems as $subitem)
                        <div class="col-12 small">
                            <div>
                                <div class="row">
                                    <div class="col-7 offset-2">{{ $subitem->quantity }} {{ $subitem->name }}
                                        @if ($subitem->observations) <span
                                                class="px-1 bg-warning text-dark">{{ $subitem->observations }}</span>
                                        @endif
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
    </div>
    @if($orderSummaryDetail->canceled == 1)
    <div class="row">
        <div class="col-6">
            <button type="button" data-dismiss="modal" class="mt-2 btn btn-secondary">Fechar <i class="fas fa-times"></i></button>
        </div>
        <div class="col text-right">
            <button type="button" name="nextStep" value="nextStep" wire:click="nextStep({{ $orderSummaryDetail->id }})" class="mt-2 btn btn-lg btn-danger text-uppercase">
                <i wire:loading wire:target="nextStep" class="fas fa-cog fa-spin"></i> Tirar do painel <i class="fas fa-trash-alt"></i></button>
        </div>
    </div>
    @elseif (isset($productionLine))
    <div class="row">
        <div class="col-6">
            @if ($productionLine->can_pause && $orderSummaryDetail->paused != 1)
                <button type="button" name="pause" value="pause" wire:click="pause({{ $orderSummaryDetail->id }})" class="mt-2 btn btn-warning">
                    <i wire:loading wire:target="pause" class="fas fa-cog fa-spin"></i> Pausar <i class="fas fa-pause"></i>
                </button>
            @endif
             <button type="button" data-dismiss="modal" class="mt-2 btn btn-secondary">Fechar <i class="fas fa-times"></i></button>
        </div>
        <div class="col text-right">
            <button type="button" name="nextStep" value="nextStep" wire:click="nextStep({{ $orderSummaryDetail->id }})" class="mt-2 btn btn-lg btn-success text-uppercase">
                <i wire:loading wire:target="nextStep" class="fas fa-cog fa-spin"></i> {{ $productionLine->name }} OK <i class="fas fa-step-forward"></i></button>
        </div>
    </div>
    @endif
</div>
