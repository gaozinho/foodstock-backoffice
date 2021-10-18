<div class="order-card">

<a href="{{ route('products.edit', $product->id) }}">
    <span
        class="badge badge-{{ $product->current_stock > 0 && $product->current_stock < $product->minimun_stock ? 'warning' : ($product->current_stock <= 0 ? 'danger' : 'success') }} text-wrap w-100 text-left">
        <span class="h6">
            <b>{{ $product->current_stock > 0 ? str_pad($product->current_stock, 3, '0', STR_PAD_LEFT) : $product->current_stock }}
            &bull; {{ strlen($product->foodstock_name) > 0 ? $product->foodstock_name : $product->name }} 
            </b>
        </span>
    </span>
 </a>   

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
                

                
                </small>
        </div>
    </div>
</div>
