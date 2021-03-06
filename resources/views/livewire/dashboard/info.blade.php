<div class="mb-5">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">

    <div class="full-screen mb-3">
        <h2 class="mt-3 mb-0 pb-0"><i class="fas fa-chart-line"></i> Painel de controle</h2>
        <div class="row">
            <div class="col-12 mb-2">
                <livewire:panels.select-restaurant :page="request()->fullUrl()"/>
            </div> 
        </div>            
    </div>

    <div class="row">
        <div class="col mb-3">
            <livewire:graph.production-dash />
        </div>
        @role('admin|financeiro')
        <div class="col-md-4 mb-3">
            <livewire:report.base-panel key="{{now()}}" />
        </div>        
        @endrole
    </div>
    @role('admin|financeiro')
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <livewire:graph.orders-week />
            </div>
            <div class="col-md-6 mb-3">
                <livewire:graph.sales-week />
            </div>
        </div>
    @endrole
    @role('admin')
        <livewire:help.health-check />
    @endrole
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
@endpush
