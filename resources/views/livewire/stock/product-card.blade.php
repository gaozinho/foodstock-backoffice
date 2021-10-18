<div class="order-card">
    @php
        $parents = $this->formatParent($product->parents);
    @endphp


    <!-- Modal -->
    <div class="modal fade" id="product-modal-{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{$product->name}} está em {{count($parents)}} itens:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{$product->parents}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>

                </div>
            </div>
        </div>
    </div>

    <span
        class="badge badge-{{ $product->current_stock > 0 && $product->current_stock < $product->minimun_stock ? 'warning' : ($product->current_stock <= 0 ? 'danger' : 'success') }} text-wrap w-100 text-left">
        <span class="h6">
            <b>{{ $product->current_stock > 0 ? str_pad($product->current_stock, 3, '0', STR_PAD_LEFT) : $product->current_stock }}
            &bull; <a class="text-white" href="{{ route('products.edit', $product->id) }}">{{ $product->name }} </b>
            @if(count($parents) == 1)
                ({{ $parents[0] }})
            @endif
            </a>
        </span>
    </span>
    

    <div class="input-group input-group-sm mb-2 mt-1">
        <div class="input-group-prepend">
            <button class="btn btn-outline-secondary minus btn-sm" type="button"><i class="fas fa-minus"></i></button>
        </div>
        <input type="text" wire:keydown.enter="moveStock" class="form-control current_stock text-right"
            wire:model.defer="add_to_current_stock" value="">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary plus btn-sm" type="button"><i class="fas fa-plus"></i></button>
        </div>
        <div class="input-group-append">
            <button wire:click="moveStock" class="btn btn-outline-secondary btn-sm" type="button">
                <i wire:loading wire:target="moveStock" class="fas fa-cog fa-spin"></i>
                <i class="fas fa-check"></i></button>
        </div>
    </div>
    <div style="line-height: 1" class="row">
        <div class="col-sm-12">
            <small><span class="h6"><b>Atual: {{ $product->current_stock }}</b></span> • Minimo: {{ $product->minimun_stock }} • Código:
                {{ $product->external_code }}
                
                @if(count($parents) > 1)
                    <span class="badge badge-secondary p-1"  data-toggle="modal" data-target="#product-modal-{{ $product->id }}">
                        <i class="fas fa-info-circle"></i> {{count($parents)}}
                    </span>
                @endif
                
                </small>
        </div>
    </div>
</div>
