<div>
    <div class="card card-painel">
        <div class="card-header">
            
            <span class="h4">Linha de produção</span><br />
            <small>Acompanhe a quantidade de pedidos em cada etapa do processo em tempo real.</small>
        </div>

        @livewire('util.progressbar', ['livewireListener' => 'render_dash', 'progressEnclosure' => '.dash-progress', 'seconds' => 60])

        <div class="card-body">
            <div class="row">
                @if(count($productionMovements) == 0)
                <div class="col-md-12">
                    Não há pedidos em sua linha de produção neste momento.
                </div>                
                @endif
                @foreach($productionMovements as $productionMovement)
                    @php
                        $icon = "";
                        if($productionMovement->role_id == 1) $icon = "blender";
                        elseif($productionMovement->role_id == 2) $icon = "layer-group";
                        elseif($productionMovement->role_id == 3) $icon = "utensils";
                        elseif($productionMovement->role_id == 4) $icon = "motorcycle";
                        elseif($productionMovement->role_id == 5) $icon = "utensils";
                    @endphp
                <div class="col-md-3 col-6">
                    <a href="{{ route('panels.production-line-panel.index', ['role_name' => $productionMovement->role]) }}">
                        <div class="card-counter primary" style="background-color: {{$productionMovement->color}}">
                            <i class="fa fa-{{$icon}}"></i>
                            <span class="count-numbers">{{$productionMovement->total}}</span>
                            <span class="count-name">{{$productionMovement->name}}</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>  
</div>
@push('scripts')
    <script src="{{ asset('js/jquery.progressBarTimer.js') }}" type="text/javascript" charset="utf-8"></script>
@endpush    