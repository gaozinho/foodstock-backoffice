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
        certificate : '',
        signature : '',
        jobName : 'Impressao foodStock',
        logo : '',
        qr : '',
        //currentPrinter : ''
    }

    qzConnection = null;
    printerConnection = null;
    orderLines = [];
    printerName = null;
    encoding = null;

    constructor(printerName, encoding, config) {
        this.encoding = encoding;
        this.printerName = printerName;
        //console.log(config);
        if(this.isObject(config)) this.config = config;
    }

    qzConnect(){
        this.setCertificates();
        return qz.websocket.connect();
    }

    qzDisconnect(){
        return qz.websocket.disconnect();
    }

    qzPrint(printerConfig, body){
        return qz.print(printerConfig, body);  
    }            

    printerConfig(printerName, encoding, jobName){
        return qz.configs.create(printerName, { 
                encoding : encoding,
                jobName : jobName
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

    simpleLine(text, tabItem){
        if (typeof tabItem !== 'undefined' && tabItem === true) text = '   ' + text;
        this.addPrintCommand(text).breakLine();
        return this;
    }

    justifiedLine(itemName, quantity, value, tabItem){
        value = this.formatMoney(value);
        if(quantity == 0) quantity = '';
        else quantity = ('' + quantity).padStart(2, '0') + ' ';
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
        var _config = this.config;
        // O arquivo cert ?? o gerado na etapa 3 e se chama privateKey.pfx
        qz.security.setCertificatePromise(function(resolve, reject) {
            $.ajax({ url: _config.certificate, cache: false, dataType: "text" }).then(resolve, reject);
        });


        qz.security.setSignaturePromise(function(toSign) {
            return function(resolve, reject) {
            $.post(_config.signature, {request: toSign}).then(resolve, reject);
            };
        });				
    }

    availablePrinters(){
        return qz.printers.details();
    }
    
    print(callback){
        this.setCertificates();
        var _this = this;
        if (qz.websocket.isActive() === true) {
            _this.doPrint(_this, callback);
        }else{
            this.qzConnect().then(function(){
                _this.doPrint(_this, callback);
            }).catch(function(err) {
                //console.log(err);
                throw new QZError(err);
            });
        }
    }

    doPrint(_this, callback){
        console.log("QZ Tray Connected!");

        _this.availablePrinters().then(function(data){
            console.log('QZ available Printer', data);
        }).catch(function(err) {
            //console.log(err);
            throw new QZError(err);
        });

        if(_this.printerName == '')  throw new PrintError("Printer not defined.");
        _this.qzPrint(_this.printerConfig(_this.printerName, _this.encoding, _this.config.jobName), _this.fullPrintCommands())
            .then(function(){
                console.log('QZ Print OK');
                callback('Tudo certo!','Pedido enviado para sua fila de impress??o. Verifique se a impressora est?? ligada e se o pedido foi impresso corretamente.','success');
                //Contabilizar impress??o
            })
            .catch(function(err) {
                console.log(err);
                callback('Ops!', 'Ocorreu um erro! Verifique se a impressora est?? ligada ou se escolheu a impressora t??rmica correta. <small>(' + err + ')</small>', 'error');
                //throw new PrintError(err);
            });                              
    }

    fromJson(jsonString){
        var jsonObject = JSON.parse(jsonString);
        //console.log(jsonObject);

        this.alingCenter();
        if(this.config.logo != ''){
            this.addLogo(this.config.logo)
                .breakLine();
        }
        this.startBold().simpleLine("PEDIDO " + jsonObject.shortOrderNumber)
            .simpleLine(jsonObject.brokerName)
            .stopBold()
            .alignLeft()
            .horizontalLine();
        
        for(var i = 0; i < jsonObject.items.length; i++){
            var totalValue = jsonObject.items[i].quantity * jsonObject.items[i].unitPrice;
            this.justifiedLine(jsonObject.items[i].name, jsonObject.items[i].quantity, totalValue, false);
            if(jsonObject.items[i].observations != null && jsonObject.items[i].observations.length > 0){
                this.startBold().simpleLine(jsonObject.items[i].observations, true).stopBold();
            }
            
            for(var j = 0; j < jsonObject.items[i].subitems.length; j++){
                var totalValueSub = jsonObject.items[i].subitems[j].quantity * jsonObject.items[i].subitems[j].unitPrice;
                this.justifiedLine(jsonObject.items[i].subitems[j].name, jsonObject.items[i].subitems[j].quantity, totalValueSub, true);
                if(jsonObject.items[i].subitems[j].observations != null && jsonObject.items[i].subitems[j].observations.length > 0){
                    this.startBold().simpleLine(jsonObject.items[i].subitems[j].observations, true).stopBold();
                }                
            }
        }

        this.horizontalLine()
            .justifiedLine("Subtotal", 0, jsonObject.subtotal, false)
            .justifiedLine("Entrega (+)", 0, jsonObject.deliveryFee, false)
            .justifiedLine("Descontos (-)", 0, jsonObject.benefitsTotal, false)
            .startBold()
            .justifiedLine("Total", 0, jsonObject.orderAmount, false)
            .stopBold()
            .horizontalLine();

            if(jsonObject.customerName != null && jsonObject.customerName.length > 0)
                this.startBold()
                    .simpleLine("Cliente: " + jsonObject.customerName)
                    .stopBold();

            if(jsonObject.deliveryFormattedAddress != null && jsonObject.deliveryFormattedAddress.length > 0)
                this.simpleLine(jsonObject.deliveryFormattedAddress);
            
            this.alingCenter()
            .horizontalLine().breakLine();

            if(this.config.qr.length > 0){
                this.addLogo(this.config.qr)
                    .breakLine()
                    .simpleLine("Acompanhe este pedido pelo celular");
            }

            this.simpleLine("-- N??O ?? DOCUMENTO FISCAL --");
        return this;
    }
}