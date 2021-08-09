<div class="card card-painel">
    <div class="card-header">
        <div class="row justify-content-between">
            <div class="col"><span class="h4">Vendas por dia</span> <span class="text-muted">R$</span></div>
            <div class="col text-right"><button wire:click="render" class="btn btn-sm btn-secondary"><i wire:loading wire:target="render"
                class="fas fa-cog fa-spin"></i> Atualizar dados</button></div>
        </div>
    </div>
    <div class="card-body">
        @if($lineChartModel->data->count() > 0)
            <div style="height: 16rem;">
                <livewire:livewire-line-chart
                                key="{{ $lineChartModel->reactiveKey() }}"
                                :line-chart-model="$lineChartModel"
                            />
            </div>
        @else
            Ainda não existem dados de pedidos para esta exibição.
        @endif        
    </div>
</div>
@push('scripts')

    <script>
        function reloadPage(){
            return setInterval(() => { 
                if (document.hasFocus()){
                    Livewire.emit('render_sales');
                }
            }, 300000);
        }

        $(document).ready(function() {
            reloadPage();
        });

    </script>
@endpush    