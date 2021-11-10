

function printDiagnostic(config){

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
                    selected : (config.currentPrinter == item.name)
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
            .simpleLine("Rua Joaquina de Paula Corrêa, 1000")
            .simpleLine("Recanto da Lagoa - Lagoa Santa")
            .alingCenter()
            .breakLine()
            .breakLine()
            .simpleLine("NÃO É DOCUMENTO FISCAL");

        //printer.fromJson('{!!$order_json!!}');

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