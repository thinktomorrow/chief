@push('custom-scripts')
<script>
    $R.options = {
        plugins: ['alignment', 'rich-links'],
        @if(admin()->hasRole('developer'))
        buttons: ['html', 'format', 'bold', 'italic', 'lists', 'link'],
        @else
        buttons: ['format', 'bold', 'italic', 'lists', 'link'],
        @endif
        formatting: ['p', 'h2', 'h3'],
        definedlinks: '{{ route('chief.api.internal-links') }}',
    };
</script>
@endpush

<script>
    // Load redactor for all data-editor instances
    $R('[data-editor]');
</script>