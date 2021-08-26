<div>

    <div class="row">
        <div class="col-lg-8 col-md-8 margin-tb">

            <div class="card border mb-4">
                <div class="card-body">
 

                    <div class="row justify-content-between">
                        <div class="col-md-8 align-self-end">
                            <span class="h3">Configure as integrações</span>
                            <p class="mb-2" style="line-height: 1.2">O foodStock buscará os pedidos no marketplaces e colocará nos paineis para que você acompanhe e movimente a produção.</p>
                        </div>
                        @if ($wizard)
                            <div class="col-auto">
                                <div class="form-group text-right mx-4">
                                    <button wire:click="continue('wizard.production-line.index')" type="button" name="save" value="ok"
                                        class="btn btn-success btn-lg"> <i wire:loading wire:target="continue" class="fas fa-cog fa-spin"></i>
                                        Continuar <i
                                        class="fas fa-forward"></i></button>
                                </div>
                            </div>
                        @endif 
                    </div>
                </div>
            </div>

            @foreach ($restaurants as $index => $restaurant)
                <livewire:configuration.broker.restaurant :index="++$index" :restaurant="$restaurant" key="{{now()}}" />
            @endforeach

        </div>
        <div class="col-lg-4 col-md-4 margin-tb">
            <div class="card">
                <div class="card-body">
                                    <h3>
                        Sobre as integrações
                    </h3>
                @foreach($brokers as $broker)
                    <small>{!!$broker->guidelines!!}</small>
                @endforeach
                </div>
            </div>
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