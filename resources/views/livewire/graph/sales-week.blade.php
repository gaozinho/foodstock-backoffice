<div class="card">
    <div class="card-header">
        <div class="row justify-content-between">
            <div class="col"><span class="h4">Vendas por dia</span> <span class="text-muted">R$</span></div>
            <div class="col text-right"><button wire:click="render" class="btn btn-sm btn-secondary"><i wire:loading wire:target="render"
                class="fas fa-cog fa-spin"></i> Atualizar dados</button></div>
        </div>
    </div>
    <div class="card-body">
        <div style="height: 16rem;">
            <livewire:livewire-line-chart
                            key="{{ $lineChartModel->reactiveKey() }}"
                            :line-chart-model="$lineChartModel"
                        />
        </div>
    </div>
</div>
@push('scripts')

    <script>
        function reloadPage(){
            return setInterval(() => { 
                Livewire.emit('render_sales');
                }, 300000);
        }

        $(document).ready(function() {
            reloadPage();
        });

    </script>
@endpush    