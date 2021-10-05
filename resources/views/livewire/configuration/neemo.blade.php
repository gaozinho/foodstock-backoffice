<div class="card border">
    <div class="card-header" id="broker-{{$restaurant->id}}">
        <div class="row align-items-center">
            <div class="col-auto">
                @if ($neemo->logo != '' && Storage::exists('public/' . $neemo->logo))
                    <img src="{{ asset('storage/' . $neemo->logo) }}" style="width: 75px">
                @else
                    <span class="h3 card-title">
                        {{ $neemo->name }}
                    </span>
                @endif            
            </div>
            <div class="col-auto">
                @if ($neemoBroker->id > 0 && $neemoBroker->validated)
                        <span class="text-success"><i class="fas fa-lg fa-check"></i> {{$restaurant->name}} está conectado ao {{ $neemo->name }}.</span>
                        @if ($neemoBroker->enabled == 0)
                        <div class="text-danger mb-2"><small><i class="fas fa-lg fa-info-circle"></i>  Você desativou temporariamente esta integração.</small></div>
                        @endif
                @else
                        <span class="text-danger"><i class="fas fa-lg fa-exclamation-triangle"></i> {{$restaurant->name}} ainda não está está conectado ao {{ $neemo->name }}.</span>
                @endif                
            </div>
            <div class="col-auto">
                @if ($neemoBroker->id > 0 && $neemoBroker->validated)
                    <button class="btn btn-secondary" type="button" data-toggle="collapse"
                        data-target="#collapsebroker-{{$neemoBroker->id}}-{{$restaurant->id}}" aria-expanded="true" aria-controls="collapsebroker-{{$restaurant->id}}">
                        Revisar configurações
                    </button>                  
                @else
                    <button class="btn btn-success" type="button" data-toggle="collapse"
                        data-target="#collapsebroker-{{$neemoBroker->id}}-{{$restaurant->id}}" aria-expanded="true" aria-controls="collapsebroker-{{$restaurant->id}}">
                        Conectar agora
                    </button>             
                @endif
            </div>

        </div>
    </div>

    <div id="collapsebroker-{{$neemoBroker->id}}-{{$restaurant->id}}" class="collapse {{$opened ? 'show' : ''}}" aria-labelledby="broker-{{$restaurant->id}}" data-parent="#broker-accordion">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    @if ($brokerAction == $neemoBroker->id && count($errors) > 0)
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
                    {!! Form::model($neemoBroker, ['method' => 'POST', 'wire:submit.prevent' => 'save']) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <strong>Token de acesso (fornecido pelo Neemo) *</strong>
                                    {!! Form::text('token', $neemoBroker->accessToken, ['wire:model.defer' => 'neemoBroker.accessToken', 'class' => 'form-control']) !!}
                                    <small>Formato do token: 00aa0a0aa0b00bb0a000a00aa0a00000</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="pretty p-switch p-fill">
                                        <input wire:model.defer='neemoBroker.enabled' name="enabled"
                                            type="checkbox" value="1"
                                            {{ old('enabled', optional($neemoBroker)->enabled) == '1' ? 'checked' : '' }} />
                                        <div class="state">
                                            <label>Ativar integração com o {{ $neemo->name }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="pretty p-switch p-fill">
                                        <input wire:model.defer='neemoBroker.acknowledgment'
                                            name="acknowledgment" type="checkbox" value="1"
                                            {{ old('acknowledgment', optional($neemoBroker)->acknowledgment) == '1' ? 'checked' : '' }} />
                                        <div class="state">
                                            <label>Confirmar automaticamente pedidos do {{ $neemo->name }}.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <div class="form-group text-right mb-0">
                            @if ($neemoBroker->id > 0)
                                <button wire:click="delete" type="button" name="delete" value="ok"
                                    class="btn btn-danger font-weight-bold mb-2"><i
                                        class="fas fa-trash"></i> <i wire:loading wire:target="delete"
                                        class="fas fa-cog fa-spin"></i>
                                    Excluir integração</button>
                            @endif
                            <button wire:click="save" type="button" name="save" value="ok"
                                class="btn btn-success text-dark font-weight-bold text-uppercase mb-2"><i
                                    class="fas fa-save"></i> <i wire:loading wire:target="save"
                                    class="fas fa-cog fa-spin"></i>
                                Salvar</button>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
</div>



