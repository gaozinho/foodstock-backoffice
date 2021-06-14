<div>
    <div class="card" style="border: 1px !importnt">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 col-md-8 margin-tb">

                    <div class="row align-items-center">
                        <div class="col-md-auto">
                            @if($ifood->logo != "" && Storage::exists('public/' . $ifood->logo))
                                <img src="{{ asset('storage/' . $ifood->logo) }}" style="height:70px" class="mb-4">
                            @else
                                <h3 class="card-title">
                                    {{ $ifood->name }}
                                </h3>
                            @endif  
                        </div>
                        @if($ifoodBroker->id > 0)
                            @if($ifoodBroker->validated)
                            <div class="col text-success">
                                <h4><i class="fas fa-lg fa-check"></i> 
                                <p class="mt-2">{{ $ifood->name }} conectado.</p>
                                </h4>
                            </div>
                            @else
                            <div class="col text-danger">
                                <h4><i class="fas fa-lg fa-exclamation-triangle"></i> 
                                <p class="mt-2">Não conseguimos contactar o {{ $ifood->name }} com os dados informados.</p>
                                </h4>
                            </div>
                            @endif
                        @endif
                    </div>

                    <!-- TODO - Make component -->
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            @if ($brokerAction == $ifoodBroker->id && count($errors) > 0)
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
                    
                        <!-- TO-DO - Fazer componente -->
                        <div class="col-lg-12 margin-tb">
                            {!! Form::model($ifoodBroker, ['method' => 'POST', 'wire:submit.prevent' => 'saveIfood']) !!}
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>ID de cliente *</strong>
                                        {!! Form::text('merchant_id', $ifoodBroker->merchant_id, ['wire:model.defer' => 'ifoodBroker.merchant_id', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                    
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <div class="pretty p-switch p-fill">
                                            <input id="enabled" wire:model.defer='ifoodBroker.enabled' name="enabled" type="checkbox" value="1"
                                                {{ old('enabled', optional($ifoodBroker)->enabled) == '1' ? 'checked' : '' }} />
                                            <div class="state">
                                                <label>Ativar integração com o {{ $ifoodBroker->name }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <div class="pretty p-switch p-fill">
                                            <input id="acknowledgment" wire:model.defer='ifoodBroker.acknowledgment' name="acknowledgment" type="checkbox" value="1"
                                                {{ old('acknowledgment', optional($ifoodBroker)->acknowledgment) == '1' ? 'checked' : '' }} />
                                            <div class="state">
                                                <label>Avisar o {{ $ifoodBroker->name }} a cada pedido recebido</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>                            
                        
                            </div>    
                            @if($brokerAction == $ifoodBroker->id)
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
                            @endif                    
                            <div class="form-group text-right">
                                @if($ifoodBroker->id > 0)
                                <button wire:click="deleteIfood" type="button" name="deleteIfood" value="ok"
                                    class="btn btn-secondary pr-4 pl-4 font-weight-bold"><i
                                        class="fas fa-trash"></i> <i wire:loading wire:target="deleteIfood" class="fas fa-cog fa-spin"></i>
                                    Excluir integração</button>
                                @endif
                                <button wire:click="saveIfood" type="button" name="save" value="ok"
                                    class="btn btn-dark pr-4 pl-4 font-weight-bold text-uppercase"><i
                                        class="fas fa-save"></i> <i wire:loading wire:target="saveIfood" class="fas fa-cog fa-spin"></i>
                                    Salvar</button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-md-4 margin-tb">
                    {!! $ifood->guidelines !!}
                </div>

            </div>
        </div>
    </div>

    <hr />

    <div class="card mt-4" style="border: 1px">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 col-md-8 margin-tb">

                    <div class="row align-items-center">
                        <div class="col-md-auto">
                            @if($rappi->logo != "" && Storage::exists('public/' . $rappi->logo))
                                <img src="{{ asset('storage/' . $rappi->logo) }}" style="height:70px" class="mb-4">
                            @else
                                <h3 class="card-title">
                                    {{ $rappi->name }}
                                </h3>
                            @endif  
                        </div>
                        @if($rappiBroker->id > 0)
                            @if($rappiBroker->validated)
                            <div class="col text-success">
                                <h4><i class="fas fa-lg fa-check"></i> 
                                <p class="mt-2">{{ $rappi->name }} conectado.</p>
                                </h4>
                            </div>
                            @else
                            <div class="col text-danger">
                                <h4><i class="fas fa-lg fa-exclamation-triangle"></i> 
                                <p class="mt-2">Não conseguimos contactar a {{ $rappi->name }} com os dados informados.</p>
                                </h4>
                            </div>
                            @endif
                        @endif
                    </div>

                    <!-- TODO - Make component -->
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            @if ($brokerAction == $rappiBroker->id && count($errors) > 0)
                                <div class="alert alert-danger">
                                    <p><strong>Ops!</strong> Temos alguns problemas.</p>
                                    <ul>
                                        @foreach ($errors->all() as $key => $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    
                        <!-- TO-DO - Fazer componente -->
                        <div class="col-lg-12 margin-tb">
                            {!! Form::model($rappiBroker, ['method' => 'POST', 'wire:submit.prevent' => 'saveRappi']) !!}
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>ID de cliente *</strong>
                                        {!! Form::text('client_id', $rappiBroker->client_id, ['wire:model.defer' => 'rappiBroker.client_id', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                    
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>Chave do cliente *</strong>
                                        {!! Form::text('client_secret', $rappiBroker->client_secret, ['wire:model.defer' => 'rappiBroker.client_secret', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>Token de acesso *</strong>
                                        {!! Form::text('token', $rappiBroker->token, ['wire:model.defer' => 'rappiBroker.token', 'class' => 'form-control']) !!}
                                    </div>
                                </div>  
                            </div>
                    
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <div class="pretty p-switch p-fill">
                                            <input id="enabled" wire:model.defer='rappiBroker.enabled' name="enabled" type="checkbox" value="1"
                                                {{ old('enabled', optional($rappiBroker)->enabled) == '1' ? 'checked' : '' }} />
                                            <div class="state">
                                                <label>Ativar integração com o {{ $rappiBroker->name }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <div class="pretty p-switch p-fill">
                                            <input id="acknowledgment" wire:model.defer='rappiBroker.acknowledgment' name="acknowledgment" type="checkbox" value="1"
                                                {{ old('acknowledgment', optional($rappiBroker)->acknowledgment) == '1' ? 'checked' : '' }} />
                                            <div class="state">
                                                <label>Avisar o {{ $rappiBroker->name }} a cada pedido recebido</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>                            
                        
                            </div>    
                    
                            @if($brokerAction == $rappiBroker->id)
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
                            @endif

                            <div class="form-group text-right">
                                @if($rappiBroker->id > 0)
                                <button wire:click="deleteRappi" type="button" name="deleteRappi" value="ok"
                                    class="btn btn-secondary pr-4 pl-4 font-weight-bold"><i
                                        class="fas fa-trash"></i> <i wire:loading wire:target="deleteRappi" class="fas fa-cog fa-spin"></i>
                                    Excluir integração</button>
                                @endif
                                <button wire:click="saveRappi" type="button" name="saveRappi" value="ok"
                                    class="btn btn-dark pr-4 pl-4 font-weight-bold text-uppercase"><i
                                        class="fas fa-save"></i> <i wire:loading wire:target="saveRappi" class="fas fa-cog fa-spin"></i>
                                    Salvar</button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-md-4 margin-tb">
                    {!! $rappi->guidelines !!}
                </div>
            </div>
        </div>
        @if($wizard)
        <hr />
        <div class="form-group text-right mx-4">
            <button wire:click="continue('wizard.production-line.index')" type="button" name="save" value="ok"
                class="btn btn-success pr-4 pl-4 text-dark font-weight-bold text-uppercase"> <i wire:loading wire:target="continue" class="fas fa-cog fa-spin"></i>
                Continuar <i
                class="fas fa-forward"></i></button>
        </div>
        @endif      
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
