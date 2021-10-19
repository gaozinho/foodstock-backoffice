<div class="card card-painel">
    <div class="card-header">
        <span class="h4">Relatórios</span><br />
        <small>Escolha a data para emissão do relatório.</small>

        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-calendar"></i></div>
            </div>
            <input type="text" wire:model.defer="selectDate" class="form-control report-datepicker" id="report-datepicker"
                placeholder="dd/mm/aaaa">
        </div>


    </div>

    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <livewire:report.production-time key="{{now()}}" :selectDate="$selectDate" />
        </li>
        <li class="list-group-item">
            <livewire:report.sales-per-day-users key="{{now()}}" :selectDate="$selectDate" />
        </li>        
    </ul>

</div>
@push('scripts')
    <script src="{{ asset('jquery-ui-1.12.1.custom/jquery-ui.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <link href="{{ asset('jquery-ui-1.12.1.custom/jquery-ui.min.css') }}" rel="stylesheet" type="text/css">

    <script>
        $(document).ready(function() {
            $('.report-datepicker').datepicker({
                dateFormat: 'dd/mm/yy',
            });

            $('.report-datepicker').on('change', function(e) {
                @this.set('selectDate', e.target.value);
            });
        });
    </script>
@endpush
