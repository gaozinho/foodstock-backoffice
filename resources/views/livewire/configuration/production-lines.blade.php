<div>
    <form id="form_production_line">
        <input id="jsonProductionLines" class="jsonProductionLines" wire:model="jsonProductionLines" type="hidden"
            name="jsonProductionLines" value="{{ $jsonProductionLines }}" />


        <div class="row">
            <div class="col-lg-8 col-md-12 margin-tb">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">

                            <div class="col-lg-12 col-md-12 text-right">
                                <button type="button" class="btn btn-primary" id="bt_add_item">
                                    <i class="fa fa-plus"></i> Etapa
                                </button>

                                <button onclick="defaultProcess()" type="button" name="createDefaultProductionLine"
                                    value="ok" class="btn btn-secondary">
                                    Restaurar padrão</button>
                            </div>
                        </div>
                        <div>
                            <ul id="items_list" class="list-group">
                                @foreach ($productionLines as $index => $productionItem)
                                    <li style="list-style:none;"
                                        class="an_item {{ intval($productionItem->production_line_id) > 0 ? 'ml-5' : '' }}">
                                        <ul class="list-group list-group-horizontal mb-2">
                                            <li class="col-sm-3 list-group-item list-group-item-secondary">
                                                <input class="step" type="hidden" name="step"
                                                    value="{{ $productionItem->step }}" />


                                                <input class="father_step" type="hidden" name="father_step"
                                                    value="{{ is_object($productionItem->productionLine) ? $productionItem->productionLine->step : '' }}" />
                                                <h3>Etapa <span class="step_number">{{ $productionItem->step }}</span>
                                                    <i
                                                        class="fa {{ intval($productionItem->production_line_id) > 0 ? 'fa-outdent' : 'fa-indent' }} indent_item"></i>
                                                </h3>
                                                <input name="color" style="width:100px;" class="color"
                                                    value="{{ $productionItem->color }}" />
                                            </li>
                                            <li class="col list-group-item">
                                                <div class="row justify-content-between mb-2">
                                                    <div class="col-md-10">
                                                        <div class="form-row">
                                                            <div class="col">
                                                                {!! Form::select('role_id', $roles, $productionItem->role_id, ['class' => 'form-control form-control-sm']) !!}
                                                            </div>
                                                            <div class="col">
                                                                {!! Form::text('name', $productionItem->name, ['placeholder' => 'Nome personalizado', 'class' => 'form-control form-control-sm']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col text-right">
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger text-right bt_trash_item">
                                                            <i class="fa fa-lg fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-auto">
                                                                <div class="pretty p-switch p-fill">
                                                                    <input name="clickable" class="clickable"
                                                                        type="checkbox"
                                                                        value="{{ $productionItem->step }}"
                                                                        {{ old('clickable', optional($productionItem)->clickable) == '1' ? 'checked' : '' }} />
                                                                    <div class="state">
                                                                        <label>Clicável</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="pretty p-switch p-fill">
                                                                    <input name="see_previous" type="checkbox"
                                                                        value="{{ $productionItem->step }}"
                                                                        class="see_previous"
                                                                        {{ old('see_previous', optional($productionItem)->see_previous) == '1' ? 'checked' : '' }} />
                                                                    <div class="state">
                                                                        <label>Incluir pedidos da etapa anterior</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-auto">
                                                                <div class="pretty p-switch p-fill">
                                                                    <input name="next_on_click" type="checkbox"
                                                                        value="{{ $productionItem->step }}"
                                                                        class="next_on_click"
                                                                        {{ old('next_on_click', optional($productionItem)->next_on_click) == '1' ? 'checked' : '' }} />
                                                                    <div class="state">
                                                                        <label>Próxima etapa ao clicar</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="pretty p-switch p-fill">
                                                                    <input name="can_pause" type="checkbox"
                                                                        value="{{ $productionItem->step }}"
                                                                        class="can_pause"
                                                                        {{ old('can_pause', optional($productionItem)->can_pause) == '1' ? 'checked' : '' }} />
                                                                    <div class="state">
                                                                        <label>Etapa pode ser "pausada"</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="pretty p-switch p-fill">
                                                                    <input name="ready" type="checkbox"
                                                                        value="{{ $productionItem->step }}"
                                                                        class="ready"
                                                                        {{ old('ready', optional($productionItem)->ready) == '1' ? 'checked' : '' }} />
                                                                    <div class="state">
                                                                        <label>Prato pronto ao final desta etapa</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                @endforeach

                            </ul>

                            <div>
                                @if (session()->has('success'))
                                    <div class="alert alert-success alert-dismissible fade show mt-2">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <i class="fa fa-check mr-1"></i>
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible fade show mt-2">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <i class="fa fa-times mr-1"></i>
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                            @if ($wizard)
                                <div class="row mb-2">
                                    <div class="col-lg-12 col-md-12 text-right">
                                        <button type="button" onclick="saveProcess()"
                                            class="btn btn-success pr-4 pl-4 text-dark font-weight-bold text-uppercase"
                                            id="bt_salvar">
                                            <i style="display: none" class="fas fa-cog fa-spin loading"></i>
                                            Finalizar <i class="fas fa-forward"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="row mb-2">
                                    <div class="col-lg-12 col-md-12 text-right">
                                        <button type="button" onclick="saveProcess()" class="btn btn-success btn-lg"
                                            id="bt_salvar">
                                            <i style="display: none" class="fas fa-cog fa-spin loading"></i>
                                            <i class="fa fa-save"></i> Salvar nova versão
                                        </button>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 margin-tb">
                <div class="card">
                    <div class="card-body">
                        <h4>
                            Configure seu processo de produção
                        </h4>

                        <p>
                            O processo é dividido em etapas. Cada etapa tem um item no menu "Acompanhe".
                        </p>
                        <p>
                            Funciona assim: projetamos o foodStock para trabalhar com painéis e cada etapa é um painel.
                            Por exemplo: o painel da cozinha recebe os pedidos novos e, assim que o pedido é preparado,
                            o cozinheiro deve passar o pedido para a próxima etapa. Esta é a lógica básica até o último
                            passo, que, por padrão é a "expedição" (que você pode customizar).
                        </p>
                        <p>
                            Crie quantas etapas achar necessário, reordene-as ou retire-as.
                        </p>
                        <h5>
                            Explicando as opções
                        </h5>

                        <small>
                            <ul>
                                <li>Clicável: indica que o cartão do pedido pode ser clicado no painel. Ao clicar
                                    aparecerá detalhes do pedido e a ação de enviar para o próximo passo.</li>
                                <li>Incluir pedidos da etapa anterior: coloca os cartões da etapa imediatamente anterior
                                    no painel corrente. </li>
                                <li>Próxima etapa ao clicar: quando se clica no cartão, não será aberta a janela de
                                    detalhes. O cartão passará imediatemente para a próxima etapa. Útil em processos
                                    onde não há necessecidade de se visualizar detalhe do pedido.</li>
                                <li>Etapa pode ser "pausada": coloca o cartão em pausa na etapa corrente, até que alguém
                                    retorne e envie o cartão para a próxima etapa.</li>
                                <li>Prato pronto ao final desta etapa: em alguns casos você quer informar ao market
                                    place (iFood, Rappi etc) que o prato está pronto. Esta opção pode não funcionar em
                                    todos market places.</li>
                            </ul>
                        </small>
                    </div>
                </div>
            </div>

        </div>


        <div class="modal fade" tabindex="-1" role="dialog" id="confirm-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Atenção!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Você está prestes a atualizar seu processo e existe(m) {{ $countAlive }} pedido(s) em
                            andamento em seu delivery.<br><br>
                            <b>Remomendamos que os pedidos sejam concluidos em seu delivery antes de alterar o
                                processo</b>.
                            <br><br>Ao confirmar o novo processo
                            <b>os pedidos voltarão ao início</b> do processo. <br><br>Deseja prosseguir?
                        </p>
                    </div>
                    <div class="modal-footer">


                        <button style="display: none" type="button" class="btn btn-danger btn-sm bt-first-step"
                            onclick="confirmFirstStep()"><i style="display: none"
                                class="fas fa-cog fa-spin loading"></i> <i class="fas fa-fast-backward"></i> Sim!
                            Colocar pedidos no início do processo</button>
                        <button style="display: none" type="button" class="btn btn-danger btn-sm bt-default"
                            onclick="confirmDefault()"><i style="display: none" class="fas fa-cog fa-spin loading"></i>
                            <i class="fas fa-fast-backward"></i> Sim! Colocar pedidos no início do processo</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não! Cancelar
                            (recomendado)</button>
                    </div>
                </div>
            </div>
        </div>


    </form>
</div>

@push('scripts')
    <script>
        //Retira alerta após 3 segundos
        document.addEventListener("DOMContentLoaded", function(event) {
            Livewire.hook('message.received', (message, component) => {
                setTimeout(function() {
                    $('.alert').fadeOut('fast');
                }, 3000);
            });
        });

        //JSON COM PROCESSO DE PRODUÇÃO

        function parseFormJson() {
            const data = new FormData($("#form_production_line")[0]);
            const production = Object.fromEntries(data.entries());
            production.color = data.getAll("color");
            production.clickable = data.getAll("clickable");
            production.father_step = data.getAll("father_step");
            production.name = data.getAll("name");
            production.next_on_click = data.getAll("next_on_click");
            production.ready = data.getAll("ready");
            production.role_id = data.getAll("role_id");
            production.see_previous = data.getAll("see_previous");
            production.step = data.getAll("step");
            production.can_pause = data.getAll("can_pause");
            return production;
        }

        function confirmFirstStep() {
            $(".loading").show();

            Livewire.emit('confirmFirstStep', JSON.stringify(parseFormJson()));
        }

        function confirmDefault() {
            $(".loading").show();
            Livewire.emit('confirmDefault');
        }

        function defaultProcess() {
            $(".loading").hide();
            @if ($countAlive > 0)
                $('#confirm-modal').modal();
                $(".bt-first-step").hide();
                $(".bt-default").show();
                $("#confirm-modal").appendTo("body");
            @else
                confirmDefault();
            @endif
        }

        function saveProcess() {
            //$(".loading").hide();
            @if ($countAlive > 0)
                $('#confirm-modal').modal();
                $(".bt-first-step").show();
                $(".bt-default").hide();
                $("#confirm-modal").appendTo("body");
            @else
                confirmFirstStep();
            @endif
        }

        $(document).ready(function() {

            var optionsDragDrop = {
                update: function(event, ui) {
                    reorderSteps();
                },
            };

            var optionsSpectrum = {
                showPaletteOnly: true,
                togglePaletteOnly: true,
                togglePaletteMoreText: 'mais',
                togglePaletteLessText: 'menos',
                cancelText: 'cancelar',
                chooseText: 'pegar',
                palette: [
                    ["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"],
                    ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"],
                    ["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9",
                        "#ead1dc"
                    ],
                    ["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6",
                        "#d5a6bd"
                    ],
                    ["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3",
                        "#c27ba0"
                    ],
                    ["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"],
                    ["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"],
                    ["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"]
                ]
            };

            function reorderSteps() {
                var items = $(".an_item");
                $.each(items, function(i, item) {

                    var currentStep = i + 1;

                    item = $(item);

                    item.find('.clickable').val(currentStep);
                    item.find('.ready').val(currentStep);
                    item.find('.see_previous').val(currentStep);
                    item.find('.can_pause').val(currentStep);
                    item.find('.next_on_click').val(currentStep);


                    if (isFirstItem(item)) {
                        outdent(item);
                    }
                    item.find(".step").val(currentStep);

                    if (isFirstItem(item) || !isIndented(item)) {
                        item.find(".father_step").val(""); //Se não indentado, é pais
                    } else {
                        var fatherItem = getFatherItem(item);
                        item.find(".father_step").val(fatherItem.find(".step").val()); //Step do item pai
                    }
                    item.find(".step_number").html(currentStep);
                });
            }

            function actionTrash() {
                if ($(".bt_trash_item").length > 1) {
                    var wrapper = $(this).closest(".an_item");

                    wrapper.fadeOut("slow", function() {
                        $(this).remove();
                        reorderSteps();
                    });

                } else {

                }

            }

            function isFirstItem(item) {
                var previous = item.prev();
                var checkIndent = previous.find('.indent_item');
                return checkIndent.length == 0;
            }

            function getFatherItem(item) {
                var isFather = false;
                var fatherItem;
                var previous = item.prev();
                while (!isFather) {
                    var itemObject = previous.find('.indent_item');
                    if (itemObject.length == 0) {
                        isFather = true;
                        fatherItem = item; //Não tem itens anteriores. Retorna ele mesmo.
                    } else if (itemObject.hasClass('fa-indent')) {
                        fatherItem = previous;
                        isFather = true;
                    }
                    previous = previous.prev();
                }
                return fatherItem
            }

            function indentItem() {
                var item = $(this).closest(".an_item");
                if (!isFirstItem(item)) {
                    if (!isIndented(item)) {
                        indent(item);
                    } else {
                        outdent(item);
                    }
                }
                reorderSteps();
            }

            function isIndented(item) {
                return item.find('.indent_item').hasClass('fa-outdent');
            }

            function outdent(item) {
                var btnDent = item.find('.indent_item');
                item.removeClass("ml-5");
                btnDent.removeClass('fa-outdent').addClass('fa-indent');
            }

            function indent(item) {
                var btnDent = item.find('.indent_item');
                item.addClass("ml-5");
                btnDent.removeClass('fa-indent').addClass('fa-outdent');
            }

            $(".color").spectrum(optionsSpectrum); // Color picker
            $("#items_list").sortable(optionsDragDrop); //Drag and drop
            $(".bt_trash_item").on("click", actionTrash); //Lixeira
            $(".indent_item").on("click", indentItem); //Indentação

            //Adiciona etapa
            $("#bt_add_item").click(function() {
                var item = $(".an_item").first().clone();
                item.find('input[name="name"]').val("");
                item.find('input[type="checkbox"]').prop("checked", false);
                item.find('.sp-replacer').remove();
                item.find('.color').spectrum(optionsDragDrop);
                item.find(".bt_trash_item").on("click", actionTrash);
                item.find(".indent_item").on("click", indentItem);
                item.appendTo("#items_list").hide().fadeIn("slow");

                reorderSteps();
            });
        });
    </script>

    <script src="{{ asset('js/spectrum/spectrum.js') }}" type="text/javascript" charset="utf-8"></script>
    <link href="{{ asset('js/spectrum/spectrum.css') }}" rel="stylesheet" type="text/css">

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">

    <script>
        //LISTENERS

        Livewire.on('reloadColors', optionsSpectrum => {
            $(".color").spectrum(optionsSpectrum);
            $('#confirm-modal').modal('hide');
        })
    </script>
@endpush
