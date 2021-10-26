<div>
    <h5>
        Importe produtos do seu cardápio
    </h5>
    <div class="row">
        <div class="col-12 mb-3">
            <p>
                Nós importamos para você os seus produtos. Assim você não precisa recadastrá-los. Escolha um cardápio específico ou importe todos eles de uma só vez.
            </p>

            <table class="table table-striped table-hover table-bordered table-sm ">

                <tbody>
                    <tr>
                        <th>Loja/Cardápio</th>
                        <th class="text-center" style="width:1%" nowrap>Importar</th>
                    </tr>
                    @foreach($catalogs as $merchant => $data)
                        <tr>
                            <th colspan="3"><small>{{$merchant}}</small></th>
                        </tr>
                        @foreach($data["catalogs"]  as $catalog)
                            <tr>
                                <td class="pl-4">
                                    <small>
                                        @foreach($catalog->context as $context)
                                            {{($context == "DEFAULT" ? 'Entrega' : ($context == "EASY_DELIVERY" ? 'Entrega Fácil' : $context))}}
                                        @endforeach
                                    </small>
                                </td>
                                <td class="text-center">
                                    @if($importIfoodRunning)
                                        
                                            <i class="fas fa-cog fa-spin"></i>
                                        
                                    @else
                                        <small><a wire:click="confirmImportIfood({{$data["restaurant"]->id}}, '{{$catalog->catalogId}}')" class="btn btn-danger btn-sm">
                                            <i wire:loading wire:target="confirmImportIfood({{$data["restaurant"]->id}}, '{{$catalog->catalogId}}')" class="fas fa-cog fa-spin"></i>
                                            <i class="fas fa-download"></i>
                                        </a></small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            @if($importIfoodRunning)
                <script>
                    function chechImportIfood() {
                        return setInterval(() => {
                            try {
                                Livewire.emit('checkImportIfood');
                            } catch (e) {
                                location.reload();
                            }
                        }, 30000);
                    }

                    $(document).ready(function() {
                        chechImportIfood();
                    });
                </script>
                <a class="btn btn-secondary btn-sm">
                    <img src="{{ asset('images/ifood-white.png') }}" style="width: 40px">
                    <i class="fas fa-cog fa-spin"></i>
                    Importando produtos. Aguarde!
                </a>
            @else
                @php
                    $restaurants = [];
                    foreach ($check as $key => $value) {
                        $restaurants[] = $key;
                    }
                @endphp
                @if (count($check) > 0)
                    <a wire:click="confirmImportIfood" class="btn btn-danger btn-sm">
                        <img src="{{ asset('images/ifood-white.png') }}" style="width: 40px">
                        <i wire:loading wire:target="confirmImportIfood" class="fas fa-cog fa-spin"></i>
                        Importar todos os cardápios
                    </a>
                @else
                    Você ainda não configurou seu delivery.
                    <a href="{{ route('configuration.broker.index') }}" class="btn btn-danger btn-sm">
                        Configurar delivery
                    </a>
                @endif
            @endif


        </div>
    </div>
</div>
