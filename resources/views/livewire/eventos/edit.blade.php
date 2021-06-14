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
            {!! Form::model($evento, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'wire:submit.prevent' => 'save']) !!}

            @csrf

            <div class="form-group">
                <strong>Nome:</strong>
                {!! Form::text('nome', $evento->nome, ['wire:model.defer' => 'evento.nome', 'class' => 'form-control']) !!}
            </div>

            <div wire:ignore>
                <script>
                    tinymce.init({
                        selector: '#descricao',
                        menubar: false,
                        plugins: [
                            'advlist autolink lists link image charmap print preview anchor',
                            'searchreplace visualblocks code fullscreen',
                            'insertdatetime media table paste code help wordcount'
                        ],
                        toolbar: 'bold italic | alignleft aligncenter ' +
                            'alignright alignjustify | bullist numlist outdent indent | ' +
                            'removeformat'
                    });

                </script>

                <div class="form-group">
                    <strong>Descrição:</strong>
                    {!! Form::textarea('descricao', $evento->descricao, ['wire:model.defer' => 'evento.descricao', 'class' => ['form-control', 'tiny'], 'id' => 'descricao']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Data início:</strong>
                        <div class="input-group mb-3 date" id="picker-data_inicio" data-target-input="nearest">
                            {!! Form::text('data_inicio', $evento->data_inicio, [
    'wire:model.defer' => 'evento.data_inicio',
    'placeholder' => '',
    'data-target' => '#picker-data_inicio',
    'class' => 'form-control data_inicio datetimepicker-input',
]) !!}
                            <div class="input-group-append" data-target="#picker-data_inicio"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Data início:</strong>
                        <div class="input-group mb-3 date" id="picker-data_fim" data-target-input="nearest">
                            {!! Form::text('data_inicio', $evento->data_inicio, [
    'wire:model.defer' => 'evento.data_fim',
    'placeholder' => '',
    'data-target' => '#picker-data_fim',
    'class' => 'form-control data_fim datetimepicker-input',
]) !!}
                            <div class="input-group-append" data-target="#picker-data_fim" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <strong>Link:</strong>
                {!! Form::text('link', $evento->link, ['wire:model.defer' => 'evento.link', 'class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                <strong>Arquivo:</strong>
                <div>
                    <input type="file" wire:model="imagem" />
                </div>
            </div>

            <div class="form-group">

                @if ($imagem)
                    <div class="card row mt-2 col-xl-4 col-sm-6 col-12">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media d-flex">
                                    <img src="{{ $imagem->temporaryUrl() }}" style="height:70px"
                                        class="rounded float-left">
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(intval($evento->id) > 0 && $evento->imagem != "" && Storage::exists('public/' .
                    $evento->imagem))
                    <div class="row mt-2">
                        <div class="col-xl-4 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-center">
                                                <img src="{{ asset('storage/' . $evento->imagem) }}"
                                                    style="height:70px" class="rounded float-left">
                                            </div>
                                            <div class="media-body pl-3">
                                                @if (intval($evento->id) > 0)
                                                    <div><a class="btn btn-link btn-sm" wire:click="downloadImagem"><i
                                                                class="fas fa-download"></i> Download</a></div>
                                                @endif
                                                <div><a class="btn btn-link btn-sm" wire:click="removeImagem"><i
                                                            class="fas fa-trash"></i> Apagar</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>



            <div class="form-group">
                <strong>Ativo:</strong>

                <label for="is_active">
                    <input id="is_active" wire:model.defer='evento.is_active' name="is_active" type="checkbox" value="1"
                        {{ old('is_active', optional($evento)->is_active) == '1' ? 'checked' : '' }}>
                    Sim
                </label>

            </div>
            <div class="form-group">
                <button wire:click="save(true)" type="button" name="save" value="ok"
                    class="btn btn-success pr-4 pl-4 text-dark font-weight-bold text-uppercase"><i
                        class="fas fa-save"></i> <i wire:loading wire:target="save" class="fas fa-cog fa-spin"></i>
                    Salvar</button>
                <button wire:click="cancel()" name="cancelar" value="ok" type="button"
                    class="btn pr-4 pl-4 text-dark font-weight-bold text-uppercase"><i class="fas fa-arrow-left"></i>
                    Cancelar</button>
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

    <script>
        $(document).ready(function() {

            //Datepicker
            $('#picker-data_inicio, #picker-data_fim').datetimepicker({
                locale: 'pt-br',
                format: 'DD/MM/YYYY'
            });

            //Máscaras dos inputs
            new Cleave('.data_inicio', {
                date: true,
                delimiter: '/',
                datePattern: ['d', 'm', 'Y']
            });
            new Cleave('.data_fim', {
                date: true,
                delimiter: '/',
                datePattern: ['d', 'm', 'Y']
            });
        });

    </script>
