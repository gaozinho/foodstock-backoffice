<div>
    <div class="small mx-4 mb-3">
        <div class="row pb-2">
            <div class="col-2 col-lg-1 col-sm-1 small font-weight-bold">Qtde</div>
            <div class="col-7 col-lg-8 col-sm-8 small font-weight-bold">Item</div>
            <div class="col-3 col-lg-3 small text-right font-weight-bold">Total</div>
        </div>
        @foreach ($orderSummaryDetail->orderBabelized->items as $item)
            <div>
                <div class="row pb-2">
                    <div class="col-2 col-lg-1 col-sm-1">
                        <h4 class="m-0">{{ $item->quantity }}x</h4>
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
                                    <div class="col-7 offset-2"><span class="h5">{{ $subitem->quantity }}x</span> {{ $subitem->name }}
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
</div>
