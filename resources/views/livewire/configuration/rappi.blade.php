<div>

    <div class="row">
        <div class="col-lg-8 col-md-8 margin-tb">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-auto">
                            @if ($rappi->logo != '' && Storage::exists('public/' . $rappi->logo))
                                <img src="{{ asset('storage/' . $rappi->logo) }}" style="height:70px" class="mb-4">
                            @else
                                <h3 class="card-title">
                                    {{ $rappi->name }}
                                </h3>
                            @endif
                        </div>
                        @if ($rappiBroker->id > 0)
                            @if ($rappiBroker->validated)
                                <div class="col text-success">
                                    <h4><i class="fas fa-lg fa-check"></i>
                                        <p class="mt-2">{{ $rappi->name }} conectado.</p>
                                    </h4>
                                </div>
                            @else
                                <div class="col text-danger">
                                    <h4><i class="fas fa-lg fa-exclamation-triangle"></i>
                                        <p class="mt-2">Não conseguimos contactar a {{ $rappi->name }} com os dados
                                            informados.</p>
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
                            {!! Form::model($rappiBroker, ['method' => 'POST', 'wire:submit.prevent' => 'save']) !!}
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
                                            <input id="enabled" wire:model.defer='rappiBroker.enabled' name="enabled"
                                                type="checkbox" value="1"
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
                                            <input id="acknowledgment" wire:model.defer='rappiBroker.acknowledgment'
                                                name="acknowledgment" type="checkbox" value="1"
                                                {{ old('acknowledgment', optional($rappiBroker)->acknowledgment) == '1' ? 'checked' : '' }} />
                                            <div class="state">
                                                <label>Avisar o {{ $rappiBroker->name }} a cada pedido
                                                    recebido</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if ($brokerAction == $rappiBroker->id)
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
                                @if ($rappiBroker->id > 0)
                                    <button wire:click="deleteRappi" type="button" name="deleteRappi" value="ok"
                                        class="btn btn-secondary pr-4 pl-4 font-weight-bold"><i
                                            class="fas fa-trash"></i> <i wire:loading wire:target="deleteRappi"
                                            class="fas fa-cog fa-spin"></i>
                                        Excluir integração</button>
                                @endif
                                <button wire:click="save" type="button" name="save" value="ok"
                                    class="btn btn-dark pr-4 pl-4 font-weight-bold text-uppercase"><i
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
        <div class="col-lg-4 col-md-4 margin-tb">
            <div class="card">
                <div class="card-body">
                    {!! $rappi->guidelines !!}
                </div>
            </div>
        </div>
    </div>

</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">

@push('scripts')


@endpush
