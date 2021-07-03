<div>

    @foreach($brokers as $broker)
        @livewire('configuration.' . $broker->code)
        <hr />
    @endforeach

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