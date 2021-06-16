<div>
    <div class="card" style="border: 1px !importnt">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12 margin-tb">

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
                                <p class="mt-2">O {{ $ifood->name }} está conectado!.</p>
                                </h4>
                            </div>
                            @else
                            <div class="col text-danger">
                                <h4><i class="fas fa-lg fa-exclamation-triangle"></i> 
                                <p class="mt-2">Seu delivery ainda não está integrado com o {{ $ifood->name }}. <span class="text-muted">Siga os passos abaixo.</span></p>
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
                            {!! Form::model($ifoodBroker, ['method' => 'POST', 'wire:submit.prevent' => 'save']) !!}
                            @if(!$ifoodBroker->validated != "")
                            <div class="row mb-3">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <h3>Passo 1</h3>
                                    <p>Gere o código de autorização para que o FoodStock acesse o seu delivery no iFood.</p>
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="form-group">
                                                <button wire:click="generateIfoodCode" type="button" name="generateIfoodCode" value="ok"
                                                    class="btn btn-danger"><i wire:loading wire:target="generateIfoodCode" class="fas fa-cog fa-spin"></i>
                                                    Gerar código de acesso do FoodStock ao iFood</button>
                                            </div>
                                        </div>
                                        @if($ifoodBroker->userCode != "")
                                        <div class="col-auto">
                                            <div class="form-group">
                                                {!! Form::text('userCode', $ifoodBroker->userCode, ['class' => 'form-control', 'id' => 'userCode', 'readonly']) !!}
                                                <small class="text-muted">O código gerado expira em <span id="clock"></span>.</small>
                                            </div>
                                        </div>   
                                        <script>
                                            $(document).ready(function() {

                                                function startCountdown(time){
                                                    $('#clock').countdown(time, function(event) {
                                                        $(this).html(event.strftime('%M:%S'));
                                                    }).on('update.countdown', function(event) {
                                                        if (event.offset.hours == 0 && event.offset.minutes == 0 && event.offset.seconds == 1) {
                                                            Livewire.emit('regenerateCode');
                                                        }
                                                    });
                                                }

                                                startCountdown('{{ $ifoodBroker->usercode_expires }}');

                                                window.addEventListener('reloadCountdown', event => {
                                                    startCountdown(event.detail.time);
                                                });
                                            });
                                        </script>                                          
                                        @endif
                                                    
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($ifoodBroker->userCode != "")
                                @if(!$ifoodBroker->validated != "")
                                    <script>
                                        $(document).ready(function() {
                                            $('#bt_copy_ifoodcode').on("click", function(){
                                                var clipboardText = "";
                                                clipboardText = $('#userCode').val(); 
                                                copyToClipboard( clipboardText );
                                                
                                            })
                                        });
                                    </script>
                                    <!--
                                    <div class="row mb-3">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="row">
                                                <h3>Passo 2</h3>
                                                <p>Copie ou anote o código abaixo. <small class="text-muted">O código gerado expira em XXX. Não deixe para depois.</small></p>
                                                <div class="col-3">
                                                    {!! Form::text('userCode', $ifoodBroker->userCode, ['class' => 'form-control', 'id' => 'userCode', 'readonly']) !!}
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" name="copy_ifoodcode" id="bt_copy_ifoodcode" value="ok"
                                                    class="btn btn-danger">Copiar código</button>
                                                </div>             
                                            </div>
                                        </div>
                                    </div>
                                    -->

                                    <div class="row mb-3">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <h3>Passo 2</h3>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="ml-4">
                                                        <ul>
                                                            <li>Acesse o seu portal iFood pelo link abaixo.</li>
                                                            <li>Clique em <b>autorizar.</b> (1)</li>
                                                        <!-- <li>Informe o código {{$ifoodBroker->userCode}} no iFood</li>--> 
                                                            <li><b>Copie ou anote</b> o código de autorização (2) gerado pelo iFood.</li>
                                                        </ul>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <a target="_blank" href="{{$ifoodBroker->verificationUrlComplete}}"
                                                            class="btn btn-danger">
                                                            Acessar portal do iFood</a>
                                                        </div>                                        
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-12 col-md-6 mt-1">
                                                    <img src="{{ asset('images/ifood-step1.png') }}" style="width:150px" class="mb-4">
                                                    <img src="{{ asset('images/ifood-step2.png') }}" style="width:150px" class="mb-4">
                                                </div>
                                            </div>
                                        </div>
                                    </div>                            
                                    <div class="row mb-3">

                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <h3>Passo 3</h3>
                                            <p>Copie o <b>código de autorização</b> (2) gerado no portal do iFood, cole no campo abaixo e clique em <b>validar</b>. </p>
                                            <div class="row">
                                                <div class="col-3">
                                                    <input type="text" wire:model.defer='ifoodBroker.authorizationCode' class="form-control" />
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" wire:click="validateIfoodCode" name="validate_ifoodcode" id="bt_validate_ifoodcode" value="ok"
                                                    class="btn btn-danger"><i wire:loading wire:target="validateIfoodCode" class="fas fa-cog fa-spin"></i> Validar</button>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>    
                                @endif
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <div class="pretty p-switch p-fill">
                                                <input id="enabled" wire:model.defer='ifoodBroker.enabled' name="enabled" type="checkbox" value="1"
                                                    {{ old('enabled', optional($ifoodBroker)->enabled) == '1' ? 'checked' : '' }} />
                                                <div class="state">
                                                    <label>Ativar imediatamente integração com o {{ $ifood->name }} ao salvar</label>
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
                                                    <label>Avisar o {{ $ifood->name }} a cada pedido recebido.</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="form-group text-right">
                                    @if($ifoodBroker->id > 0)
                                    <button wire:click="delete" type="button" name="delete" value="ok"
                                        class="btn btn-secondary pr-4 pl-4 font-weight-bold"><i
                                            class="fas fa-trash"></i> <i wire:loading wire:target="delete" class="fas fa-cog fa-spin"></i>
                                        Excluir / reconfigurar integração</button>
                                    @endif
                                    <button wire:click="save" type="button" name="save" value="ok"
                                        class="btn btn-dark pr-4 pl-4 font-weight-bold text-uppercase"><i
                                            class="fas fa-save"></i> <i wire:loading wire:target="save" class="fas fa-cog fa-spin"></i>
                                        Salvar</button>
                                </div>                            
                            @endif
      


                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-md-12 margin-tb">
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
@push('scripts')

<script type="text/javascript" src="{{ asset('js/jquery_countdown/jquery.countdown.min.js') }}"></script>



<script>
    

    function copyToClipboard(text) {
        var textArea = document.createElement( "textarea" );
        textArea.value = text;
        document.body.appendChild( textArea );       
        textArea.select();
        try {
           var successful = document.execCommand( 'copy' );
        } catch (err) {
           console.log('Oops, unable to copy',err);
        }    
        document.body.removeChild(textArea);
        Livewire.emit('copied', text);
     }


</script>
@endpush    