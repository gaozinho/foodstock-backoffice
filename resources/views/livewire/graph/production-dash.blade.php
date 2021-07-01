<div>
    <div class="card">
        <div class="card-header">
            <span class="h4">Linha de produção</span> <small>(tempo real)</small>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($productionMovements as $productionMovement)
                    @php
                        $icon = "";
                        if($productionMovement->role_id == 1) $icon = "blender";
                        elseif($productionMovement->role_id == 2) $icon = "layer-group";
                        elseif($productionMovement->role_id == 3) $icon = "utensils";
                        elseif($productionMovement->role_id == 4) $icon = "motorcycle";
                        elseif($productionMovement->role_id == 5) $icon = "utensils";
                    @endphp
                <div class="col-md-3">
                    <div class="card-counter primary" style="background-color: {{$productionMovement->color}}">
                        <i class="fa fa-{{$icon}}"></i>
                        <span class="count-numbers">{{$productionMovement->total}}</span>
                        <span class="count-name">{{$productionMovement->name}}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@push('scripts')

    <script>
        function reloadPage(){
            return setInterval(() => { 
                Livewire.emit('render_dash');
                }, 180000);
        }

        $(document).ready(function() {
            reloadPage();
        });

    </script>
@endpush    