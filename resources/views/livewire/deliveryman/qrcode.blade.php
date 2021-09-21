<div>
    <div class="row">
        <div class="col-lg-12 text-center mt-4">
            <h1>{!! $restaurants !!}</h1>
            <h3>Acompanhe se seu pedido está em preparo ou pronto.</h3>
            <h4>Aponte sua câmera para o QR Code.</h4>
        </div>     
    </div>
    <div class="row justify-content-center">
        <div class="col-12 text-center mt-4">
        {!! QrCode::size(700)->generate($this->qrCodeUrl); !!}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-center mt-4">
            <h1>www.foodstock.com.br</h1>
            <h4>Conheça o foodStock - seu delivery simples!</h4>
        </div>     
    </div>    
</div>
@push('scripts')
    <script>
        window.print();
    </script>
@endpush