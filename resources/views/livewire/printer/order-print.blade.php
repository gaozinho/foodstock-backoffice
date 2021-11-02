    <div class="row">
        <div class="col-lg-8 col-md-8 margin-tb">


            
            <div class="card border mb-4">
                <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <span class="h3">Configure sua impressora</span>
                            <p class="mb-2 mt-3" style="line-height: 1.2">Para ajudar em seu processo, o foodStock permite que você imprima seu pedido em impressoras térmicas não fiscais.</p>
                            <p class="mb-2" style="line-height: 1.2">Atualmente é possível imprimir apenas de seu computador com o Windows instalado. A versão do foodStock acessada pelo celular ou tablet ainda não suporta impressão.</p>
                        </div>

                        <div class="col-lg-12 margin-tb mt-4" wire:ignore>
                            <span class="h4">Parâmetros</span>
                            <table class="table table-striped table-hover table-bordered table-sm mt-2">
                                <tbody>
                                    <tr>
                                        <th>Item</th>
                                        <th>Configurado?</th>
                                    </tr>
                                    <tr>
                                        <td>Cliente QZ Tray</td>
                                        <td>
                                            <i class="fas fa-cog fa-spin loading"></i>
                                            <span class="qz-tray-nok" style="display: none"><i class="fas fa-lg fa-times text-danger"></i> QZ Tray não instalado ou não inicializado. <b>Siga o passo 2 da configuração.</b></span>
                                            <span class="qz-tray-ok" style="display: none"><i class="fas fa-lg fa-check text-success"></i> Cliente de impressão QZ Tray instalado!</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:1%" nowrap>Qual sua impressora térmica?</td>
                                        <td>

   

                                            <i class="fas fa-cog fa-spin loading"></i>
                                            <span class="mb-2 printer-available-nok" style="display: none"><i class="fas fa-lg fa-times text-danger"></i> Não conseguimos listar as impressoras disponíveis em seu computador. Verifique se sua impressora está corretamente instalada.</span>
                                            
                                                 @if(!isset($printer->available) || $printer->available == 0)
                                                        <div class="mt-1 print-test-ok" style="display: none"><i class="fas fa-lg fa-info-circle"></i> Escolha a impressora térmica</div>
                                                    @else
                                                        <div class="mt-1 print-test-ok" style="display: none"><i class="fas fa-lg fa-check text-success"></i> Impressora {{$printerName}} configurada como sua impressora térmica!</div>
                                                    @endif                                            
                                            
                                            <select class="available-printers" style="display: none" wire:model="printerName">
                                                <option value="">Escolha uma impressora</option>
                                            </select>
                                            <div class="printer-message" style="display: none">

                                                <button class="btn btn-primary btn-sm print-test-ok mt-1" style="display:none" onclick="printTest()"><i class="fas fa-cog fa-spin loading-printer" style="display:none"></i> <i class="fas fa-lg fa-print"></i> Imprimir folha de teste</button> 
                                                
                                                    

                                                <div><small>Se você não encontrar sua impressora na lista, refaça o passo-a-passo da configuração.</small></div>
                                                
                                                                                           
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>            

            <div class="card border mb-4">
                <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <h4>
                                Para configurar sua impressora, siga os passos abaixo:
                            </h4>

                            <p>
                                1 - Sua impressora deve estar devidamente instalada em seu computador com Windows. Verifique com o fabricante ou seu suporte técnico se esta etapa já está concluída.
                            </p>
                            <p>
                                2 - É preciso instalar um programa cliente em seu computador. Este cliente é responsável por fazer a ponte entre o nosso sistema e sua impressora. <a href="{{ asset('cert/qz-tray-2.2.0.exe') }}">Baixe e instale o QZ Tray</a>. Após a instalação, reinicie o computador. Basta seguir os passos do instalador, conforme imagem abaixo.
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('images/printer/step1.png') }}">
                                </div>
                                2.1 - Depois de instalado, verifique se o QZ Tray criou um ícone na bandeja do sistema (canto inferior direito), conforme image abaixo.
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('images/printer/step2.png') }}">
                                </div>
                            </p>
                            <p>
                                3 - Para finalizar, <a href="{{ asset('cert/cert.pem') }}">vamos baixar e configurar a chave de criptografia</a> para uma comunicação segura entre o foodStock e sua impressora.
                            </p>
                            <p>
                                3.1 - Após baixar a chave acima, clique no ícone do QZ Tray, depois em "Advanced" e depois em "Site Mamager...", conforme imagem.
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('images/printer/step3.png') }}">
                                </div>
                            </p>
                            <p>
                                3.2 - Na tela que aparece, clique no sinal de "+" e, em seguida em "Browse..." para adicionar a chave que você baixou no passo anterior.
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('images/printer/step4.png') }}">
                                </div>
                            </p>   
                            <p>
                                3.3 - Escolha o arquivo "cert.pem" que você baixou, conforme imagem abaixo.
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('images/printer/step5.png') }}">
                                </div>
                            </p>  
                            <p>
                                3.4 - Pronto! Estamos prontos para imprimir os seus pedidos.
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('images/printer/step6.png') }}">
                                </div>
                            </p>                                
                        </div>
                    </div>
                </div>
            </div>            


        </div>
        <div class="col-lg-4 col-md-4 margin-tb">
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
@push('scripts')

    <script src="{{ asset('js/qz-tray.js') }}"></script>
    <script src="{{ asset('js/printer/qz-printer.js') }}"></script>

    <script>

        var config = {
            lineWidth : 42,
            certificate : '{{ asset('cert/cert.pem') }}',
            signature : '{{ route('printer.sign-message') }}',
            jobName : 'Impessão de teste foodStock'
        }

        var currentPrinter = '{{strlen($printerName) > 0 ? $printerName : ''}}';

        function printDiagnostic(){
            var printer = new PrintOrder('', 'Cp1252', config);

            printer.qzConnect().then(function(){
                //Conexão QZ Tray OK
                $(".qz-tray-ok").show();
                $(".qz-tray-nok").hide();
                $(".printer-available-nok").hide();
                $(".available-printers").show();

                printer.availablePrinters().then(function(data){
                    console.log('QZ available Printer', data);
                    $(".printer-message").show();

                    if(data.length == 0){
                        $('.printer-available-nok').show();
                        $('.available-printers').hide();
                    }

                    $.each(data, function (i, item) {
                        $('.available-printers').append($('<option>', { 
                            value: item.name,
                            text : item.name, 
                            selected : (currentPrinter == item.name)
                        }));
                    });

                    $(".print-test-ok").show();
                    $(".print-test-nok").hide(); 
                    $(".loading").hide();

                    printer.qzDisconnect();
                    
                }).catch(function(err) {
                    $(".loading").hide();
                    console.log(err);
                    printer.qzDisconnect();
                });
                

            }).catch(function(err) {
                //Erro ao conectar ao QZ Tray
                console.log(err);
                $(".loading").hide();
                $(".qz-tray-nok").show();
                $(".qz-tray-ok").hide();    
                $(".printer-available-nok").show();
                $(".available-printers").hide();  
                $(".print-test-ok").hide(); 
                $(".print-test-nok").show();  
                printer.qzDisconnect();  
            });

        }

        $(document).ready(function() {         
            var printerOk = printDiagnostic();
        });

        function printTest(){
            $(".loading-printer").show();
            var printerName = $(".available-printers").val();
            console.log(printerName);
            
            var printer = new PrintOrder(printerName, 'Cp1252', config);

            printer.alingCenter()
                    .addLogo('https://www.foodstock.com.br/images/logo.png')
                    .breakLine()
                    .startBold()
                    .simpleLine("PEDIDO 9999")
                    .stopBold()
                    .alignLeft()
                    .horizontalLine()
                    .justifiedLine("Estrogonofe", 2, 19.9, false)
                    .justifiedLine("Grande (400g)", 2, 4.9, true)
                    .horizontalLine()
                    .breakLine()
                    .startBold()
                    .simpleLine("Cliente: Wagner Gomes Gonçalves")
                    .stopBold()
                    .simpleLine("Rua Joaquina de Paula Corrêa, 910. Ap 101")
                    .simpleLine("Recanto da Lagoa - Lagoa Santa")
                    .alingCenter()
                    .breakLine()
                    .breakLine()
                    .simpleLine("NÃO É DOCUMENTO FISCAL");

            try{
                printer.print(function(title, text, type){
                    Swal.fire(title, text, type);
                    $(".loading-printer").hide();
                });
                
            }catch (err) {
                if(err instanceof PrintError){
                    //Swal.fire('Ops!', 'Não conseguimos acessar a impressora para a página de teste.', 'error');
                }else if (err instanceof QZError){
                    //Swal.fire('Ops!', 'Não conseguimos conectar ao QZ Tray.', 'error');                    
                }else {
                    //Swal.fire('Ops!', 'Ocorreu um erro! Verifique se a impressoara está ligada ou se escolheu a impressora térmica correta.', 'error');    
                }
                $(".loading-printer").hide();
            }
        }

    </script>

    <!-- Loading -->
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

@endpush 