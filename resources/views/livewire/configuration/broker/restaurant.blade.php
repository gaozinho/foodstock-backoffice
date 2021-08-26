<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="card border mb-4">
            <div class="card-body">

                @if (count($errors) > 0)
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <p><strong>Ops!</strong> Temos alguns problemas.</p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="h3 font-weight-bolder mb-4">{{ $index }} {{ empty($restaurant->name) ? 'Novo restaurante' : $restaurant->name }}</div>

                <div class="accordion" id="broker-accordion">

                    @foreach ($brokers as $broker)
                        @livewire('configuration.' . $broker->code, ["restaurant" => $restaurant])
                    @endforeach
                </div>


            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
@push('scripts')

    <script type="text/javascript" src="{{ asset('js/jquery_countdown/jquery.countdown.min.js') }}"></script>

    <script>
        function copyToClipboard(text) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                var successful = document.execCommand('copy');
            } catch (err) {
                console.log('Oops, unable to copy', err);
            }
            document.body.removeChild(textArea);
            Livewire.emit('copied', text);
        }
    </script>
@endpush