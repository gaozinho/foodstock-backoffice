<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 col-md-8 margin-tb">
                    <!-- TODO - Make component -->
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <p><strong>Ops!</strong> Temos alguns problemas.</p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        </div>
                    
                        <div class="col-lg-12 margin-tb">
                            {!! Form::model($restaurant, ['method' => 'POST', 'wire:submit.prevent' => 'save', 'id' => 'restaurant-form']) !!}
                    
                            <div class="form-group">
                                <strong>Nome fantasia de seu restaurante *</strong>
                                {!! Form::text('name', $restaurant->name, ['wire:model.defer' => 'restaurant.name', 'class' => 'form-control']) !!}
                            </div>
                    
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>Endereço *</strong>
                                        {!! Form::text('endereco', $restaurant->address, ['wire:model.defer' => 'restaurant.address', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                    
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <strong>Complemento</strong>
                                        {!! Form::text('complement', $restaurant->complement, ['wire:model.defer' => 'restaurant.complement', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                    
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <strong>CEP *</strong>
                                        {!! Form::text('cep', $restaurant->cep, ['wire:model.defer' => 'restaurant.cep', 'class' => 'cep form-control']) !!}
                                    </div>
                                </div>        
                            </div>
                    
                    
                            <div class="row">
                    
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>Site</strong>
                                        {!! Form::text('site', $restaurant->complement, ['wire:model.defer' => 'restaurant.site', 'class' => 'site form-control', 'placeholder' => 'http://']) !!}
                                    </div>
                                </div>
                    
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>E-mail *</strong>
                                        {!! Form::text('email', $restaurant->email, ['wire:model.defer' => 'restaurant.email', 'class' => 'form-control']) !!}
                                    </div>
                                </div>   
                                
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>CNPJ</strong>
                                        {!! Form::text('cnpj', $restaurant->cnpj, ['wire:model.defer' => 'restaurant.cnpj', 'class' => 'cnpj form-control']) !!}
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>Telefone *</strong>
                                        {!! Form::text('phone', $restaurant->phone, ['wire:model.defer' => 'restaurant.phone', 'class' => 'phone form-control']) !!}
                                    </div>
                                </div>           
                            </div>    
                    
                            @if($wizard)
                            <div class="form-group text-right">
                                <button wire:click="save" type="button" name="save" value="ok"
                                    class="btn btn-success pr-4 pl-4 text-dark font-weight-bold text-uppercase"> <i wire:loading wire:target="save" class="fas fa-cog fa-spin"></i>
                                    Continuar <i
                                    class="fas fa-forward"></i></button>
                            </div>
                            @else
                            <div class="form-group text-right">
                                <button wire:click="save" type="button" name="save" value="ok"
                                    class="btn btn-success pr-4 pl-4 text-dark font-weight-bold text-uppercase"><i
                                        class="fas fa-save"></i> <i wire:loading wire:target="save" class="fas fa-cog fa-spin"></i>
                                    Salvar</button>
                            </div>
                            @endif
                            <div>
                                @if (session()->has('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session()->has('error'))
                                    <div class="alert alert-error">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-md-4 margin-tb">

                    <h5>
                        Cadastre seu delivery
                    </h5>

                    <p>
                        O cadastro é simples:
                    </p>
                    <p>
                        Informe o "nome fantasia" do seu delivery. Usaremos este nome em nosso aplicativo para identificar sua marca.
                    </p>
                    <p>
                        O endereço, e-mail e telefone são importantes para entrarmos em contato para novidades e avisos em geral. Não utilizaremos estes dados para mais nada além disto.
                    </p>
                    <p>
                        Se possível informe o CNPJ e site para conhecermos um pouco mais sobre seu delivery.
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Máscaras nos campos -->
<script src="{{ asset('node_modules/cleave.js/cleave.min.js') }}"></script>

<script>
$(document).ready(function() {

    //Máscaras dos inputs
    new Cleave('.cnpj', {
        numericOnly: true,
        delimiters: ['.', '.', '/', '-'],
        blocks: [2, 3, 3, 4, 2]
    });

    new Cleave('.cep', {
        numericOnly: true,
        delimiters: ['.', '-'],
        blocks: [2, 3, 3]
    });

    /**
    new Cleave('.site', {
        prefix: 'http://',
    });    
    */

    new Cleave('.phone', {
        numericOnly: true,
        blocks: [0, 2, 0, 5, 4],
        delimiters: ["(", ")", " ", "-"]
    });    
});

</script>
