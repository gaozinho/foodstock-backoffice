<x-app-layout>
    <div class="mb-4">
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" type="text/css">

        <div class="full-screen mb-3">
            <h2 class="mt-3 mb-0 pb-0">Painel de controle</h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="h4">Linha de produção</span> <small>(tempo real)</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card-counter primary">
                                    <i class="fa fa-blender"></i>
                                    <span class="count-numbers">12</span>
                                    <span class="count-name">Cozinha</span>
                                </div>
                            </div>
                        
                            <div class="col-md-3">
                            <div class="card-counter danger">
                                <i class="fa fa-layer-group"></i>
                                <span class="count-numbers">599</span>
                                <span class="count-name">Montagem</span>
                            </div>
                            </div>
                        
                            <div class="col-md-3">
                                <div class="card-counter success">
                                    <i class="fa fa-utensils"></i>
                                    <span class="count-numbers">6875</span>
                                    <span class="count-name">Selagem</span>
                                </div>
                            </div>
                        
                            <div class="col-md-3">
                                <div class="card-counter info">
                                    <i class="fa fa-motorcycle"></i>
                                    <span class="count-numbers">35</span>
                                    <span class="count-name">Expedição</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <livewire:graph.orders-week />
            </div>   
            <div class="col-md-6">
                <livewire:graph.sales-week />
            </div>        
        </div>
    </div>
</x-app-layout>

    @push('scripts')
        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <xscript src="{{ asset('node_modules/apexcharts/apexcharts.min.js') }}"></xscript>

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

