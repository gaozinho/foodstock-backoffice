<div>
    <div class="row mb-3">
        <div class="col-lg-12 col-sm-12">
            <div class="form-row">
                <div class="col-auto">
                    <a onclick="formatEditLink('{{route('products.index')}}')" href="javascript:;" class="btn btn-primary text-uppercase"><i class="fas fa-arrow-left"></i> Voltar</a>
                </div>
            </div>
        </div>
    </div>
    {!! Form::model($product, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'wire:submit.prevent' => 'save']) !!}
    <div class="row">
        <div class="col-lg-8 col-md-12 margin-tb">
            <div class="card">
                <div class="card-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <p><strong>Whoops!</strong> Temos alguns problemas.</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">

                        <div class="col-lg-12 margin-tb">
                            <h3 class="mb-3">
                                Dados do produto
                            </h3>

                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-sm-8 col-md-8">
                                    <div class="form-group">
                                        <strong>Nome *</strong>
                                        {!! Form::text('name', $product->name, ['wire:model.defer' => 'product.name', 'class' => 'form-control', 'onClick'=>"this.select();"]) !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <strong>Código PDV:</strong>
                                        {!! Form::text('external_code', $product->external_code, ['wire:model.defer' => 'product.external_code', 'class' => 'form-control external_code', 'onClick'=>"this.select();"]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Nome no foodStock <small><span class="text-muted">Este nome será mantido para identificar seu produto no foodStock.</span></small></strong>
                                        {!! Form::text('foodstock_name', $product->foodstock_name, ['wire:model.defer' => 'product.foodstock_name', 'class' => 'form-control', 'onClick'=>"this.select();"]) !!}
                                    </div>
                                </div>
                            </div>                        

                            <div wire:ignore>
                                <script>
                                    $(document).ready(function() {
                                        //Máscaras dos inputs
                                        /*
                                        new Cleave('.unit_price', {
                                            numeral: true,
                                            numeralDecimalMark: ',',
                                            delimiter: '.'
                                        });
                                        */

                                    });
                                </script>
                            </div>

                            <div class="form-group">
                                <strong>Descrição</strong>
                                {!! Form::textarea('description', $product->description, ['wire:model.defer' => 'product.description', 'class' => ['form-control', 'tiny'], 'id' => 'description', 'rows' => '3']) !!}
                            </div>

                            <div class="form-group">
                                @if(intval($product->broker_id) > 0)
                                    <strong>Foto do produto <i wire:loading wire:target="image" class="fas fa-cog fa-spin"></i></strong>
                                    @if(filter_var($product->image, FILTER_VALIDATE_URL))
                                        <div>
                                            <img src="{{$product->image}}" style="width: 100px" class="img-thumbnail">
                                        </div>
                                    @endif
                                @else
                                    <div>
                                        <input type="file" wire:model="image" accept=".jpg,.jpeg,.png" />
                                        @error('image') 
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }} A imagem possui formato inválido ou ultrapassa o limite de tamanho.
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="text-muted"><small>Máximo 2 Megabytes</small></div>                            
                                @endif
                            </div>

                            <div class="form-group">

                                @if (!empty($image))
                                    <div class="row">
                                        <div class="col-xl-4 col-sm-6 col-12">
                                            <div class="media d-flex">
                                                <img src="{{ $image->temporaryUrl() }}" style="height:70px"
                                                    class="rounded float-left">
                                            </div>
                                        </div>
                                    </div>
                                @elseif(intval($product->id) > 0 && $product->image != "" && Storage::exists('public/' .
                                    $product->image))
                                    <div class="row">
                                        <div class="col-xl-4 col-sm-6 col-12">

                                            <div class="media d-flex">
                                                <div class="align-self-center">
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        style="height:70px" class="rounded float-left">
                                                </div>
                                                <div class="media-body pl-3">
                                                    @if (intval($product->id) > 0)
                                                        <div><a class="btn btn-link btn-sm"
                                                                wire:click="downloadImagem"><i
                                                                    class="fas fa-download"></i> Download</a>
                                                        </div>
                                                    @endif
                                                    <div><a class="btn btn-link btn-sm"
                                                            wire:click="removeImagem"><i
                                                                class="fas fa-trash"></i> Apagar</a></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <div class="pretty p-switch p-fill">
                                    <input wire:model.defer='product.enabled' name="enabled" type="checkbox" value="1"
                                        {{ old('enabled', optional($product)->enabled) == 1 ? 'checked' : '' }} />
                                    <div class="state">
                                        <label>Produdo em comercialização/ativo?</label>
                                    </div>
                                </div>
                            </div>

                            <hr />


                            <h3 class="mb-3">
                                Controle de estoque e produção
                            </h3>
            
                            <div class="form-group">
                                <div class="pretty p-switch p-fill">
                                    <input wire:model='product.monitor_stock' name="monitor_stock" type="checkbox" value="1"
                                        {{ old('monitor_stock', optional($product)->monitor_stock) == 1 ? 'checked' : '' }} />
                                    <div class="state">
                                        <label>Monitorar este produto no painel de estoque? </label>
                                    </div>
                                </div>
                                <i wire:loading wire:target="product.monitor_stock" class="fas fa-cog fa-spin"></i>
                                <div class="ml-3">
                                    <div style="line-height: 1" class="mb-2"><small class="text-muted">Monitore aqueles produtos que são críticos no seu processo de produção. No <a href="{{route('stock.panel')}}">painel de estoque</a> você receberá alertas caso o seu produto esteja perto de esgotar.</small></div>
                                    @if(optional($product)->monitor_stock == 1)
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <strong>Estoque mínimo</strong>
                                                    {!! Form::text('minimun_stock', $product->minimun_stock, ['wire:model.defer' => 'product.minimun_stock', 'class' => 'form-control text-right', 'onClick'=>"this.select();"]) !!}
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <strong>Estoque atual</strong>
                                                    {!! Form::text('current_stock', $product->current_stock, ['wire:model.defer' => 'product.current_stock', 'class' => 'form-control text-right', 'onClick'=>"this.select();"]) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" wire:ignore>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <strong>Agrupadores</strong>
                                                    <small class="text-muted">Organize como os produtos aparecerão no seu <a href="{{route('stock.panel')}}">painel de estoque</a>. Você pode criar os seus marcadores.</small>
                                                    <select wire:model="stock_panels_select" name="stock_panels" class="form-control form-select" multiple="multiple">
                                                        @foreach($stock_panels as $panel)
                                                        <option value="{{$panel->id}}">{{$panel->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>       
                                        <script>
                                            $(document).ready(function() {
                                                //Configure
                                                $('.form-select').select2({
                                                    tags: true,
                                                    createTag: function (params) {
                                                        var term = $.trim(params.term);
                                                        if (term.length < 4) {
                                                            return null;
                                                        }
                                                        return {
                                                            id: term,
                                                            text: term,
                                                            newTag: true // add additional parameters
                                                        }
                                                    }
                                                }).on("select2:select", function (e){ 
                                                    var select2Combo = $(this);
                                                    if(e.params.data.newTag){
                                                        $.post("/api/stock-panel", {
                                                                name : e.params.data.text,
                                                                ui : '{{(new \Hashids\Hashids('', 10))->encode(auth()->user()->id)}}',
                                                            }, function(data) {
                                                                select2Combo.find('[value="' + e.params.data.text + '"]')
                                                                    .replaceWith('<option selected value="' + data.data.id + '">'+e.params.data.text+'</option>');
                                                                var data = select2Combo.select2("val");
                                                                select2Combo.trigger("change");
                                                            }
                                                        );
                                                    }
                                                }).on('change', function (e) {
                                                    var data = $('.form-select').select2("val");
                                                    Livewire.emit('updatePanel', data)
                                                }); 

                                            });
                                        </script>
                                    @endif
                                                                                                     
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <strong>Este produto leva a produção para a</strong>
                                {!! Form::select('initial_step', ["0" => "Não interfere nas etapas de produção"] + $productionLines, $product->initial_step, ['wire:model.defer' => 'product.initial_step', 'class' => 'form-control mb-2']) !!}
                            </div>                        
            
                            <div class="mb-5 ml-3">
                                <small class="text-muted">
                                    <p class="mb-1">Ao receber um pedido com este produto, onde ele deverá iniciar seu processo de produção?</p>
                                    <p class="mb-1">Um prato que você mantém pronto, por exemplo, o estrogonofe. Ele não deveria passar pela <i>cozinha</i>, pois já está pronto. Talvez deva <b>iniciar diretamente na fase de <i>entrega</i></b>.</p>
                                    <p class="mb-1">Um omelete, por exemplo, geralmente é feito na hora, então faz sentido enviar o pedido para a <b>etapa da <i>{{$productionLines[array_keys($productionLines)[0]]}}</i></b>.</p>
                                    <p class="mb-1">Um refrigerante não precisa ser produzido, então deveria iniciar no passo de <b>{{end($productionLines)}}</b></p>
                                </small>
                            </div>


                            <div class="row justify-content-between">
                                <div class="col-6">
                                <a onclick="formatEditLink('{{route('products.index')}}')" href="javascript:;"
                                    class="btn pr-4 pl-4 font-weight-bold btn-secondary text-uppercase"><i
                                        class="fas fa-arrow-left"></i>
                                    Cancelar</a>
                                </div>
                                    <div class="col-6 text-right">
                                    <button wire:click="save(true)" type="button" name="save" value="ok"
                                    class="btn btn-success pr-4 pl-4 font-weight-bold text-uppercase"><i
                                        class="fas fa-save"></i> <i wire:loading wire:target="save"
                                        class="fas fa-cog fa-spin"></i>
                                    Salvar</button>  
                                    </div>                              
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

                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 margin-tb">
            <div class="card">
                <div class="card-body">
                    <h5>
                        Controle de Estoque
                    </h5>

                    <p>
                        Caso você queira ter uma visão sobre quais produtos estão acabando ou chegando perto do limite mínimo, escolha a opção "monitorar". Mostraremos em amarelo aqueles produtos que alcançaram o minimo e em vermelho aqueles com estoque zero ou negativo.
                    </p> 
                    <h5>
                        Cada pedido em seu lugar no processo
                    </h5>

                    <p>
                        Sabemos que muitos restaurantes mantê pratos prontos aguardando pedidos. Sabemos tambpem que muitos prato precisam ser cozinhados na hora.
                    </p>                       
                    <p>
                        Pensando nisso, damos a opção para você indicar qual dos seus produtos deve ir para a cozinha e quais devem ir para um ponto mais avançado no processo. Basta indicar em qual ponto o produto deve "iniciar" seu processo.
                    </p> 
                    
                    <p>
                        Para visualizar como está configurado seu processo acesse o seu <a href="{{route('configuration.production-line.index')}}">processo de produção</a>.
                    </p> 
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@push('scripts')

    <script>
        function formatEditLink(baseLink){
            var queryString = window.location.href.slice(window.location.href.indexOf('?') + 1);
            console.log(window.location.href.indexOf('?'), queryString);
            if(window.location.href.indexOf('?') < 0) window.location.href = baseLink;
            else  window.location.href = baseLink + "?" + queryString;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>        
@endpush 