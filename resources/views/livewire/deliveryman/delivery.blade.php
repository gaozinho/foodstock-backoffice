<div>
    <div class="full-screen mb-3">
        <h2 class="mt-3 mb-0 pb-0">Acompanhando 
            <span class="badge badge-secondary">{{ $total_orders }}</span> pedidos
        </h2>
        <span>Pedidos de: <b>{!! $restaurants !!}</b></span><br />
        
        <span class="legend mt-0 pt-0">Legenda:
            <span class="badge bg-danger text-white p-1">Produzindo</span> 
            <span class="badge bg-success p-1">Pronto para retirar</span> <br />
            <p style="line-height: 1"><small>Você pode acompanhar se seu pedido está pronto para retirada. Basta fornecer o número (ou conjunto de números) no campo abaixo.</small></p>
        </span>                
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-xs-12 col">
                    <div class="form-group">
                        <strong>Digite o número do pedido</strong>
                        <div class="input-group mb-3">
                            <input type="text" wire:model.defer="order_id" class="form-control upper-on-keyup" placeholder="# Pedido">
                            <div class="input-group-append">
                                <button wire:click="addOrder" class="btn btn-add btn-primary" type="button">Adicionar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div>   
    </div>    

    <div class="loading">
        <div class="">
            @if($total_orders == 0)
            <hr />
            <div class="text-center">
                <img src="{{ asset('images/ico-logo.png') }}" class="mt-2 mb-2">
                    <h3>Digite o número do pedido que deseja acompanhar.</h3>
            </div>
            @endif
            <div class="row">
                @foreach ($orderSummaries as $index => $orderSummary)
                    <div class="text-center col-12">
                        <div class="card mb-2 {{$lastStepProductionLine->id == $orderSummary->production_line_id ? 'bg-success' : 'bg-danger'}}">
                            <div class="card-body p-2">
                                <div class="text-white h4">
                                    {{ str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT) }}
                                    
                                </div>
                                <div class="m-0 p-0 small text-white">
                                    <small>{{ $orderSummary->restaurant }} &bull; {{ $orderSummary->broker }}
                                    &bull; 
                                    <span onclick='$(".loading").LoadingOverlay("show")' class="text-white" wire:click="removeOrder('{{$orderSummary->friendly_number}}')"><i class="fas fa-minus-circle"></i> remover</span>
                                </small></div>
                            </div>
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



            $(".upper-on-keyup").keyup(function () {  
                $(this).val($(this).val().toUpperCase());  
            });



            var reloadDataInterval = reloadPage();

            $('.btn-add').on('click', function(e) {
                $(".loading").LoadingOverlay("show");
            });

            $('.btn-remove').on('click', function(e) {
                $(".loading").LoadingOverlay("show");
            });            

            Livewire.on('loaded', function() {
                $(".loading").LoadingOverlay("hide");
            })

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>    
@endpush
