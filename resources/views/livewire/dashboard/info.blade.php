<div class="mb-5">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">

    <div class="full-screen mb-3">
        <h2 class="mt-3 mb-0 pb-0"><i class="fas fa-chart-line"></i> Painel de controle</h2>
    </div>

    @role('admin')
        <livewire:help.health-check />
    @endrole

    <div class="row">
        <div class="col-md-12">
            <livewire:graph.production-dash />
        </div>
    </div>
    @role('admin')
        <div class="row mt-3">
            <div class="col-md-6 mb-3">
                <livewire:graph.orders-week />
            </div>
            <div class="col-md-6 mb-3">
                <livewire:graph.sales-week />
            </div>
        </div>
    @endrole
</div>
