<div>
    <div id="countdown"></div>
    <div class="row justify-content-between align-items-center">
        <div class="col">
            <h2 class="mt-3 mb-0 pb-0"><i class="fas fa-hamburger"></i> Painel de estoque</h2>
        </div>
        <div class="col-auto">

        </div>
        <div class="col-auto">
            <span class="legend mt-0 pt-0">Legenda:
                <span class="badge badge-success">OK: Acima do mínimo</span>
                <span class="badge badge-warning" style="color: #fff; background-color: #ff8e09">Atenção: Abaixo do mínimo</span>
                <span class="badge badge-danger">Cuidado: Negativo</span>
                
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-2">
            <livewire:panels.select-restaurant :page="request()->fullUrl()" />
        </div>
    </div>


    <div class="row mt-3">
        <div class="col">
            <div class="loading">
                <div>
                        @if(count($products) == 0)
                            <hr />
                            <div class="text-center mt-5">
                                <img src="{{ asset('images/ico-logo.png') }}" class="mt-2 mb-2">
                                    <h3>Você ainda não colocou nenhum produto neste painel.</h3>
                                    <p>
                                    <a href="{{route('products.index')}}" type="button" name="products"
                                    class="btn btn-success btn-lg  mb-2"> Configurar produtos comercializados <i
                                    class="fas fa-forward"></i></a>
                                    </p>
                            </div>
                        @endif                
                    <div class="row">



                        @foreach($products as $product)
                        <div class="mb-2 col-xl-3 col-lg-3 col-md-4 col-6">
                            <div class="card-painel card-margin">
                                <div class="card-body py-3 px-3">
                                    <livewire:stock.product-card :product="$product" :wire:key="$product->id" />
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/jquery.progressBarTimer.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>

        function reloadPage(){
            return setInterval(() => { 
                Livewire.emit('loadData');
             }, 30000);
        }

        function incrementValue(e, increment){
            e.val(parseInt(e.val(), 0) + increment);
            e[0].dispatchEvent(new Event('input'));
            return e;
        }

        function progressBar(){
            $("#countdown").progressBarTimer({
                timeLimit: 30,
                warningThreshold: 5,
                autostart: false,
                smooth: true,
                onFinish : function (){ 
                    
                }
            }).start();
        }

        $(document).ready(function() {

            var reloadDataInterval = reloadPage();

            var i = 0, timeOut = 0;

            $('.plus').on('mousedown touchstart', function(e) {
                var el = $(this).parent().parent().find(".current_stock");
                timeOut = setInterval(function(){
                    i++;
                    incrementValue(el, i > 10 ? 10 : 1);
                }, 300);
            }).bind('mouseup mouseleave touchend', function() {
                clearInterval(timeOut);
                i = 0;
            }).on('click', function(){
                incrementValue($(this).parent().parent().find(".current_stock"), 1);
                reloadDataInterval = reloadPage();
            });
            
            $('.minus').on('click', function(){
                incrementValue($(this).parent().parent().find(".current_stock"), -1);
                reloadDataInterval = reloadPage();
            });          
        });
    </script>
        <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

@endpush