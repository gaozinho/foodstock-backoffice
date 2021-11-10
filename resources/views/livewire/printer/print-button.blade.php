<span>
    @if(is_object($printer) && $printer->available == 1)
     <button type="button" wire:click="printOrder({{$orderSummary->id}})" class="btn btn-sm btn-light"><i class="fas fa-cog fa-spin loading-printer" style="display:none"></i> <i class="fas fa-print"></i></button>
        @if($doPrint)
        <script>
            
            //console.log('{{$qrUrl}}');

            printOrder('{!!$orderSummary->order_json!!}', '{{$orderSummary->friendly_number}}', '{{$qrUrl}}')
        </script>
        @endif
    @endif

</span>
@push('scripts')

    <script src="{{ asset('js/qz-tray.js') }}"></script>
    <script src="{{ asset('js/printer/qz-printer.js') }}"></script>
    <script src="{{ asset('js/printer/order-print.js') }}"></script>

    <script>

        function printOrder(order_json, friendly_number, qr){
            var config = {
                lineWidth : 42,
                certificate : '{{ asset('cert/cert.pem') }}',
                signature : '{{ route('printer.sign-message') }}',
                jobName : 'Pedido ' + friendly_number,
                logo : 'https://www.foodstock.com.br/images/logo.png',
                qr : qr
            }

            try{
                $(".loading-printer").show();
                var printer = new PrintOrder('{{strlen($printer->name) > 0 ? $printer->name : ''}}', 'Cp1252', config);       
                //console.log(order_json)
                printer.fromJson(order_json);

                printer.print(function(title, text, type){
                        Swal.mixin({
                            toast: true,
                            icon: 'success',
                            title: text,
                            animation: true,
                            position: 'top-right',
                            showConfirmButton: false,
                            timer: 3000,
                            //timerProgressBar: true
                        }).fire();
                        $(".loading-printer").hide();
                    });

            }catch (err) {
                //console.log(err);
                Swal.mixin({
                        toast: true,
                        icon: 'error',
                        title: "Não conseguimos imprimir o pedido escolhido.",
                        animation: true,
                        position: 'top-right',
                        showConfirmButton: false,
                        timer: 3000,
                        //timerProgressBar: true
                    }).fire();                
                $(".loading-printer").hide();
            }              
        }      
    </script>    

    <!-- Loading -->
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

@endpush 