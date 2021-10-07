<div class="dash-progress" wire:ignore>
    <script src="{{ asset('js/component_progress.js') }}" type="text/javascript" charset="utf-8"></script>
    <script>
        $(document).ready(function() {
            refreshComponent("{{$livewireListener}}", "{{$progressEnclosure}}", {{$seconds}});
        });
    </script>
</div>