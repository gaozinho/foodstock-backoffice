<div>
    <div class="full-screen mb-3">
        <h2 class="mt-3 mb-0 pb-0">{{ $restaurant->name }}
            <span class="badge badge-secondary">{{ $total_orders }}</span>
        </h2>
        <span class="legend mt-0 pt-0">Legenda:
            <span class="badge bg-danger text-white p-1">Produzindo</span> 
            <span class="badge bg-success p-1">Pronto para retirar</span>
        </span>                
    </div>

    <div class="loading">
        <div class="">
            @if($total_orders == 0)
            <div class="text-center">
                <img src="{{ asset('images/ico-logo.png') }}" class="mt-2 mb-2">
                    <h3>Aguarde! Nenhum item em produção ou pronto.</h3>
            </div>
            @endif
            <div class="row">
                @php
                    $prevStartNumber = -1;                        
                @endphp                        
                @foreach ($orderSummaries as $index => $orderSummary)
                    @php
                        //$curStartNumber = intval(substr($orderSummary->friendly_number, 0, 1));

                        $curStartNumber = substr(str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT), 0, 1);
                        if($curStartNumber != $prevStartNumber){
                            if($prevStartNumber >= 0) echo '</div>';
                            $prevStartNumber = $curStartNumber;
                            //Abre coluna
                            if($index < count($orderSummaries)) echo '<div class="text-center col px-1">';
                        }
                    @endphp
                        <div 
                            class="order-card card mb-2 {{$lastStepProductionLine->id == $orderSummary->production_line_id ? 'bg-success' : 'bg-danger'}}">
                            <div class="card-body">
                                <h4 class="text-white">{{ str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT) }}</h4>
                                <div class="m-0 p-0 small text-white">{{ $orderSummary->broker->name }}</div>
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
                Livewire.emit('loadData');
             }, 60000);
        }
        $(document).ready(function() {
            var reloadDataInterval = reloadPage();
        });
    </script>
@endpush
