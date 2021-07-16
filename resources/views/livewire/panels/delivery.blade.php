<div>
    <div class="mb-3 full-screen">
        <div class="d-flex justify-content-between align-items-end">
            <div>
                <h2 class="mt-3 mb-0 pb-0">Painel de delivery
                    <span class="badge badge-secondary">{{ $total_orders }}</span>
                </h2>
                <span class="legend mt-0 pt-0">Legenda:
                    <span class="badge bg-danger p-1">Produzindo</span> 
                    <span class="badge bg-success p-1">Pronto para despachar / balcão / mesa</span>
                    <span class="badge" style="color: #fff; background-color: #ff8e09">Cancelado</span> 
                </span>
            </div>
            <div class="text-right">
                <div class="d-flex justify-content-between align-items-end">
                    <div class="mr-2">
                        <button class="btn btn-sm btn-primary" id="bt-fullscreen"><i class="fas fa-expand-arrows-alt"></i> Modo painel <small>(F4)</small></button>
                        <div>Acompanhe pelo seu celular.</div>
                    </div>
                    <div class="visible-print text-center mt-2">
                        {!! QrCode::size(70)->generate($this->qrCodeUrl); !!}
                        
                    </div>
                </div>
            </div>
       </div>
    </div>
    @if(!is_object($lastStepProductionLine))
    <div class="row">
        <div class="col">
            <div class="alert alert-danger">

                        <i class="fas fa-exclamation-circle"></i> Atenção! Você ainda não configurou o seu processo de produção. 
                        <br />Para que os pedidos apareçam aqui, é necessário configurar pelo menos uma <b>integração</b> e também configurar o seu <b>processo de produção</b>.
                        <br />
                        <a href="{{route('configuration.production-line.index')}}" class="btn btn-secondary">Configurar processo de produção</a>

            </div>
        </div>
    </div>
        
    @endif
    <div class="card loading">
        <div class="card-body">
            @if($total_orders == 0)
            <div class="text-center">
                <img src="{{ asset('images/ico-logo.png') }}" class="mt-2 mb-2">
                    <h3>Nenhum item em produção ou pronto.</h3>
            </div>
            @endif
            <div class="row">
                @php
                    $prevStartNumber = -1;                        
                @endphp                        
                @foreach ($orderSummaries as $index => $orderSummary)

                    @php
                        $babelized = new App\Foodstock\Babel\OrderBabelized($orderSummary->order_json);
                    @endphp

                    @php
                        $clickAction = 'wire:click="orderDetail(' . $orderSummary->id . ', ' . $orderSummary->production_line_id . ')"';
                        $curStartNumber = intval(substr($orderSummary->friendly_number, 0, 1));
                        $cardColor = "";
                        $orderNumber = str_pad($orderSummary->friendly_number, 4, "0", STR_PAD_LEFT);

                        if($orderSummary->canceled == 1){
                            $cardColor = 'style="background-color: #ff8e09 !important"';
                            $orderNumber = '<del>' . $orderNumber . '</del>';
                        }

                        if($curStartNumber != $prevStartNumber){
                            if($prevStartNumber >= 0) echo '</div>';
                            $prevStartNumber = $curStartNumber;
                            //Abre coluna
                            if($index < count($orderSummaries)) echo '<div class="text-center col">';
                        }
                    @endphp
                        <div {!!$clickAction!!}
                            onClick='$(".loading").LoadingOverlay("show")'
                            class="order-card card mb-2 {{$lastStepProductionLine->id == $orderSummary->production_line_id ? 'bg-success' : 'bg-danger'}}" {!!$cardColor!!}>
                            <div class="card-body">
                                <h4 class="text-white">{!! $orderNumber !!}</h4>
                                <div class="m-0 p-0 small text-white">{{ $orderSummary->broker->name }} :: {{$babelized->orderType}}</div>
                            </div>
                        </div>
                @endforeach
            </div>
        </div>
    </div>


    @if(is_object($orderSummaryDetail) && is_object($orderSummaryDetail->orderBabelized))
    <div class="modal fade order-modal" id="order-modal" tabindex="-1" role="dialog" aria-labelledby="order-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    @include('livewire.panels.order-header-include')
                </div>
                <div class="modal-body">
                    <div data-testid="wrapper">

                        @include('livewire.panels.order-detail-include')

                        @if($lastStepProductionLine->id == $orderSummaryDetail->production_line_id)
                        <div>
                            <div role="toolbar" class="btn-toolbar"><button type="button" name="finishProcess" value="finishProcess" wire:click="finishProcess({{$orderSummaryDetail->id}})" class="mt-2 btn btn-success btn-block"><i wire:loading wire:target="finishProcess" class="fas fa-cog fa-spin"></i> Despachar</button></div>
                        </div>
                        @endif
                        <div>
                            <div role="toolbar" class="btn-toolbar"><button type="button" data-dismiss="modal" class="mt-2 btn btn-secondary btn-block">Fechar</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


</div>
@push('scripts')
    <script>

        function reloadPage(){
            return setInterval(() => { 
                Livewire.emit('loadData');
             }, 60000);
        }

        function fullScreen(){
            $(".full-screen").hide();
            $("#main-container").attr("fullscreen" , "1").addClass("container-full").removeClass("container").removeClass("my-5");
            Swal.fire({
                text: 'Aperte ESC para sair do modo painel.',
                timer: 3000,
                showCancelButton: false,
                showCloseButton: false
            });
        }

        function cancelFullScreen(){
            $(".full-screen").show();
            $("#main-container").removeAttr("fullscreen").removeClass("container-full").addClass("container").addClass("my-5");
        }        

        $(document).ready(function() {

            $(document).keyup(function(e) {

                if (e.key === "Escape") { // escape key maps to keycode `27`
                    cancelFullScreen();
                }else if (e.key === "F4") { // escape key maps to keycode `27`
                    fullScreen();
                }
           });
            
            Livewire.hook('element.updated', (el, component) => {
                if($("#main-container").attr("fullscreen") == "1"){
                    fullScreen();
                }
            })

            $("#bt-fullscreen").on("click", function(){
                fullScreen();
            });

            var reloadDataInterval = reloadPage();

            $('#order-modal').on('hide.bs.modal', function (e) {
                reloadDataInterval = reloadPage();
            });

            $('#order-modal').on('show.bs.modal', function (e) {
                clearInterval(reloadDataInterval);
            });            

            Livewire.on('openOrderModal', function(){
                clearInterval(reloadDataInterval);
                $(".loading").LoadingOverlay("hide");
                $('#order-modal').modal();
            })

            Livewire.on('closeOrderModal', function(){
                $('#order-modal').modal('hide');
                $('.modal-backdrop').remove();
            })       
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
@endpush
