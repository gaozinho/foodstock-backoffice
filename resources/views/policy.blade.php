<x-guest-layout>

    <div class="col-md-12 text-center mb-5 mt-5">
        <h2 class="text-uppercase ftco-uppercase">Termos de Uso e Política de Privacidade</h2>
    </div>

    <x-jet-validation-errors class="mb-3 px-3" />

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    {!! $policy !!}
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>
