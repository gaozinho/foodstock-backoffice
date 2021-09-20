<div class="order-card">
    <a href="{{ route('products.edit', $product->id) }}">
        <h4><span class="badge badge-{{($product->current_stock > 0 && $product->current_stock < $product->minimun_stock) ? 'warning' : (($product->current_stock < 0) ? 'danger' : 'success')}} text-wrap w-100 text-left">{{$product->name}}</span></h4>
    </a>
    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <button class="btn btn-outline-secondary minus" type="button"><i class="fas fa-minus"></i></button>
        </div>
        <input type="text" wire:keydown.enter="moveStock" class="form-control current_stock" wire:model.defer="product.current_stock" value="{{$product->current_stock}}">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary plus" type="button"><i class="fas fa-plus"></i></button>
        </div>
        <div class="input-group-append">
            <button wire:click="moveStock" class="btn btn-outline-secondary" type="button">
            <i wire:loading wire:target="moveStock" class="fas fa-cog fa-spin"></i>
            <i class="fas fa-check"></i></button>
        </div>
    </div>
    <div style="line-height: 1" class="row">
        <div class="col-sm-12">
            <small>{{$product->restaurant->name}} - Atual: {{$product->current_stock}} • Minimo: {{$product->minimun_stock}}</small>
        </div>
    </div>
</div>