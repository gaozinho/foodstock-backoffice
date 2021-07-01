<x-app-layout>
    <div class="mb-5">
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">

        <div class="full-screen mb-3">
            <h2 class="mt-3 mb-0 pb-0">Painel de controle</h2>
        </div>

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
</x-app-layout>

    @push('scripts')
        <script src="//unpkg.com/alpinejs" defer></script>
        <xscript src="https://cdn.jsdelivr.net/npm/apexcharts"></xscript>
        <script src="{{ asset('node_modules/apexcharts/apexcharts.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                @if (session()->has('error'))
                Swal.fire({
                    icon: 'error',
                    text: '{{ session("error") }}',
                });
                @endif
            });
        </script>

    @endpush    