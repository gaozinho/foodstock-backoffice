    <div class="row">
        <div class="col-lg-8 col-md-8 margin-tb">


            <div class="card border mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                        @if($restaurantsCount == 0)
                            <span class="h3">Você ainda não tem lojas cadastradas</span>
                            <div>Cadastre pelo menos uma loja para prosseguir e integrá-la ao(s) seu(s) <i>marketplace</i>.</div>
                        @else
                            <span class="h3">Você já cadastrou {{ $restaurantsCount }} lojas</span>
                        @endif
                        </div>
                    </div>

                    <div class="row justify-content-between">
                        <div class="col-md-8 align-self-end">
                            <a wire:click="createRestaurant" class="btn btn-success btn-lg mb-2">
                                <i class="fas fa-plus"></i>
                                <i wire:loading="createRestaurant" wire:target="createRestaurant" class="fas fa-cog fa-spin"></i>
                                Criar nova loja
                            </a>
                        </div>
                        <div class="col-auto">
                            @if ($wizard && $restaurantsCount > 0)
                                <button wire:click="continue('wizard.broker.index')" type="button" name="save" value="ok"
                                    class="btn btn-success btn-lg  mb-2"> <i wire:loading wire:target="continue" class="fas fa-cog fa-spin"></i>
                                    Continuar <i
                                    class="fas fa-forward"></i></button>
                            @endif 
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($restaurants as $index => $restaurant)
                <livewire:configuration.restaurant :index="++$index" :restaurant="$restaurant" key="{{now()}}" />
            @endforeach

        </div>
        <div class="col-lg-4 col-md-4 margin-tb">
            <div class="card">
                <div class="card-body">
                    <h5>
                        Cadastre seu delivery
                    </h5>

                    <p>
                        O cadastro é simples:
                    </p>
                    <p>
                        Informe o "nome fantasia" do seu delivery. Usaremos este nome em nosso aplicativo para
                        identificar sua marca.
                    </p>
                    <p>
                        O endereço, e-mail e telefone são importantes para entrarmos em contato para novidades e avisos
                        em geral. Não utilizaremos estes dados para mais nada além disto.
                    </p>
                    <p>
                        Se possível informe o CNPJ e site para conhecermos um pouco mais sobre seu delivery.
                    </p>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <!-- Máscaras nos campos -->
    <script src="{{ asset('node_modules/cleave.js/cleave.min.js') }}"></script>

    <script>

        function mountPageComponents(){

            $('.cnpj').toArray().forEach(function (field) {
                new Cleave(field, {
                    numericOnly: true,
                    delimiters: ['.', '.', '/', '-'],
                    blocks: [2, 3, 3, 4, 2]
                });
            });

            $('.cep').toArray().forEach(function (field) {
                new Cleave(field, {
                    numericOnly: true,
                    delimiters: ['.', '-'],
                    blocks: [2, 3, 3]
                });
            }); 

            $('.phone').toArray().forEach(function (field) {
                new Cleave(field, {
                    numericOnly: true,
                    blocks: [0, 2, 0, 5, 4],
                    delimiters: ["(", ")", " ", "-"]
                });
            });   
        }

        $(document).ready(function() {         
            mountPageComponents();
        });

        Livewire.on('mountPageComponents', function(){
            mountPageComponents();
            $(".name")[0].focus(); //Foco no primeiro campo
        })
    </script>
@endpush