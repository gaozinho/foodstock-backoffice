<div class="row">


    <div class="col-lg-12 margin-tb">


        <div class="card border mb-4">




            <div class="card-body">

                @if (count($errors) > 0)
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <p><strong>Ops!</strong> Temos alguns problemas.</p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                <span class="h3 font-weight-bolder">{{ $index }}
                    {{ empty($restaurant->name) ? 'Novo restaurante' : $restaurant->name }}</span>

                {!! Form::model($restaurant, ['method' => 'POST', 'wire:submit.prevent' => 'save']) !!}

                <div class="form-group">
                    <strong>Nome fantasia de seu restaurante *</strong>
                    {!! Form::text('name', $restaurant->name, ['wire:model' => 'restaurant.name', 'class' => 'name form-control']) !!}
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


                <div class="form-group text-right mb-0">
                    @if(intval($restaurant->id) > 0)
                        <button type="button" wire:click="$emitUp('confirmDestroy', {{$restaurant->id}})" class="btn btn-danger">
                            <i class="fas fa-trash"></i> <i wire:loading class="fas fa-cog fa-spin"></i>
                        </button>
                    @endif

                    <button wire:click="save" type="button" name="save" value="ok"
                        class="btn btn-success pr-4 pl-4 text-dark font-weight-bold text-uppercase"><i
                            class="fas fa-save"></i> <i wire:loading wire:target="save" class="fas fa-cog fa-spin"></i>
                        Salvar</button>
                </div>

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
</div>
