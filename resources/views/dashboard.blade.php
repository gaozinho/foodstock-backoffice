<x-app-layout>
    <x-jet-welcome />
    @push('scripts')
        <script>
            $(document).ready(function() {
                @if (session()->has('error'))
                Swal.fire({
                    icon: 'error',
                    text: '{{ session("error") }}',
                });
                @endif
            });
        </script>
    @endpush    
</x-app-layout>

