<div class="fullpage-loading">
    @foreach($restaurants as $restaurant)
        <label class="mb-0 mt-1" onclick='$(".fullpage-loading").LoadingOverlay("show");'>
            <small>
                <input type="checkbox" value="{{ $restaurant->id }}" wire:model="selectedRestaurants.{{ $restaurant->id }}"  class="form-checkbox">
                <span class="mr-1 text-sm">{{ $restaurant->name }}</span>
            </small>
        </label>        
    @endforeach
</div>