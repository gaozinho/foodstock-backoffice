<div>
    <h1>Teste de impressão</h1>
</div>
@push('scripts')

    <script src="{{ asset('js/qz-tray.js') }}"></script>

    <script>

        class PrintError extends Error {
            constructor(message) {
                super(message);
                this.name = this.constructor.name;
            }
        }  
        
        class QZError extends Error {
            constructor(message) {
                super(message);
                this.name = this.constructor.name;
            }
        }              

        class PrintOrder {

            config = {
                lineWidth : 42,
            }

            qzConnection = null;
            printerConnection = null;
            orderLines = [];
            printerName = null;
            encoding = null;

            constructor(printerName, encoding, config) {
                this.encoding = encoding;
                this.printerName = printerName;
                if(this.isObject(config)) this.config = config;
            }

            qzConnect(callback){
                return qz.websocket.connect().then(callback);
            }

            printerConfig(printerName, encoding){
                return qz.configs.create(printerName, { 
                        encoding : encoding 
                });
            }

            isObject(val) {
                return typeof val === 'object'; 
            }

            addPrintCommand(textLine){
                this.orderLines.push(textLine);
                return this;
            }

            centeredSimpleLine(text){
                this.alingCenter().simpleLine(text).breakLine();
                return this;
            }

            simpleLine(text){
                this.addPrintCommand(text).breakLine();
                return this;
            }

            justifiedLine(itemName, quantity, value, tabItem){
                value = this.formatMoney(value);
                quantity = ('' + quantity).padStart(2, '0') + ' ';
                if (typeof tabItem !== 'undefined' && tabItem === true) quantity = '   ' + quantity;
                var whiteSpaceSize = this.config.lineWidth - (value.length + quantity.length + itemName.length);
                var formatedString = quantity + itemName + ''.padStart(whiteSpaceSize, ' ') + value;
                this.addPrintCommand(formatedString).breakLine();
                return this;
            }

            formatMoney(value){
                return 'R$' + this.formatNumber(value);
            }
            
            formatNumber(value){
                return (Math.round(value * 100) / 100).toFixed(2);
            }

            horizontalLine(){
                this.addPrintCommand(''.padStart(this.config.lineWidth,'-')).breakLine();
                return this;                    
            }

            startPrinter(){
                return '\x1B' + '\x40';                
            }

            startPrint(){
                return '\x10' + '\x14' + '\x01' + '\x00' + '\x05';
            }

            cutPaper(){
                return '\x1B' + '\x69';                
            }     
            
            finishPrint(){
                return '\x10' + '\x14' + '\x01' + '\x00' + '\x05';              
            }
            
            alingCenter(){
                this.addPrintCommand('\x1B' + '\x61' + '\x31');
                return this;                    
            }

            alignLeft(){
                this.addPrintCommand('\x1B' + '\x61' + '\x30');
                return this;
            }

            breakLine(){
                this.addPrintCommand('\x0A');
                return this;                    
            }

            startBold(){
                this.addPrintCommand('\x1B' + '\x45' + '\x0D');
                return this;
            }

            stopBold(){
                this.addPrintCommand('\x1B' + '\x45\n');
                return this;
            }

            addLogo(url){
                this.addPrintCommand({
                    type: 'raw',
                    format: 'image',
                    flavor: 'file',
                    data: url,
                    options: { language: "escp", dotDensity: 'double' },
                }).addPrintCommand('\x1B' + '\x74' + '\x10');

                return this;
            }

            fullPrintCommands(){
                return [this.startPrinter(), this.startPrint(), ...this.orderLines, 
                '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A',
                '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A',
                this.cutPaper(), this.finishPrint()];
            }
            
            setCertificates(){
                // O arquivo cert é o gerado na etapa 3 e se chama privateKey.pfx
                qz.security.setCertificatePromise(function(resolve, reject) {
                    $.ajax({ url: "http://localhost:8000/cert/cert.pem", cache: false, dataType: "text" }).then(resolve, reject);
                });


                qz.security.setSignaturePromise(function(toSign) {
                    return function(resolve, reject) {
                    $.post("http://localhost:8000/printer/sign-message", {request: toSign}).then(resolve, reject);
                    };
                });				
            }
            
            print(){
                this.setCertificates();
                var _this = this;
                qz.websocket.connect().then(function(){
                    console.log("QZ Tray Connected!");
                    var printerConfig = _this.printerConfig(_this.printerName, _this.encoding);
                    qz.print(printerConfig, _this.fullPrintCommands()).catch(function(err) {
                        console.log(err);
                        throw new PrintError(err);
                    });                        
                }).catch(function(err) {
                    console.log(err);
                    throw new QZError(err);
                });
            }
        }

        var printer = new PrintOrder('MP-100S TH', 'Cp1252');

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
            printer.print()
        }catch (err) {
            if(err instanceof PrintError){
                alert("Não foi possível imprimir.");
            }else if (err instanceof QZError){
                alert("Não conectado ao QZ Tray.");
            }else {
                throw err;
            }
        }

    </script>

    <!-- Loading -->
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

@endpush 