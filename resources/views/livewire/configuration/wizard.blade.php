<div class="pb-5">
    <link rel="stylesheet" href="{{ asset('css/wizard/style.css') }}">
    <div>
        <div class="row justify-content-center mt-0">
            <div class="col-12">
                <div class="card">
                    <div class="row text-center">
                        <div class="col-md-12 mx-0 mt-4">
                            <h2><strong>Configure seu delivery</strong></h2>
                            <p>São três passos. Simples. Rápido.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mx-0 text-center">
                                <ul id="progressbar" class="pl-0">
                                    <li {!!$wizardStep == 1 ? 'class="active"' : ''!!} id="step1"><strong>Sobre seu delivery</strong></li>
                                    <li {!!$wizardStep == 2 ? 'class="active"' : ''!!} id="step2"><strong>Integrações</strong></li>
                                    <li {!!$wizardStep == 3 ? 'class="active"' : ''!!} id="step3"><strong>Processo de produção</strong></li>
                                </ul>
                        </div>
                    </div>
                    <div class="mx-4 mb-4">
                            @if($wizardStep == 1)
                                @include('livewire.configuration.restaurants')
                            @elseif($wizardStep == 2)
                                @include('livewire.configuration.brokers')
                            @elseif($wizardStep == 3)
                                @include('livewire.configuration.production-lines')
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
