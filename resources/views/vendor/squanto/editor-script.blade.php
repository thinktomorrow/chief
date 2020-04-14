@push('custom-scripts')
    <script src="{{ asset('assets/back/js/vendor/redactor.js') }}"></script>
    <script>
        $R.options = {
            plugins: ['alignment', 'rich-links'],
            @if(chiefAdmin()->hasRole('developer'))
                buttons: ['html', 'format', 'bold', 'italic', 'lists', 'link'],
            @else
            buttons: ['format', 'bold', 'italic', 'lists', 'link'],
            @endif
            formatting: ['p', 'h2', 'h3'],
            definedlinks: '{{ route('chief.api.internal-links') }}',
        };
    </script>
@endpush

@push('custom-styles')
    <link rel="stylesheet" href="{{ asset('assets/back/css/vendor/redactor.css') }}">
@endpush

<script>
    // Load redactor for all data-editor instances
    $R('[data-editor]');
</script>
