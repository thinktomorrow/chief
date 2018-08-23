@push('custom-scripts')
    <script>
        $R.options = {
            plugins: ['redactorColumns', 'imagemanager', 'alignment', 'rich-links', 'custom-classes', 'snippets'],
            @if(admin()->hasRole('developer'))
                buttons: ['html', 'format', 'bold', 'italic', 'lists', 'image', 'file', 'link'],
            @else
                buttons: ['undo', 'format', 'bold', 'italic', 'lists', 'image', 'file', 'link'],
            @endif
            formatting: ['p', 'h1', 'h2', 'h3'],
            imageResizable: true,
            imagePosition: true,
            callbacks: {
                upload: {
                    beforeSend: function(xhr)
                    {
                        let token = document.head.querySelector('meta[name="csrf-token"]');

                        xhr.setRequestHeader('X-CSRF-TOKEN', token.content);
                    }
                }
            },
            imageUpload: '{{ $imageUploadUrl }}',
            definedlinks: '{{ route('chief.api.internal-links') }}',
            snippetslink: '{{ route('chief.api.snippets-links') }}',
            customClasses: [
                {
                    title: '<span class="icon icon-droplet"></span> link als knop',
                    'class': 'btn btn-default',
                    tags: ['a'],
                },
                {
                    title: '<span class="icon icon-droplet"></span> link als primaire knop',
                    'class': 'btn btn-primary',
                    tags: ['a'],
                },
                {
                    title: '<span class="icon icon-droplet"></span> link as secundaire knop',
                    'class': 'btn btn-secondary',
                    tags: ['a'],
                },
                {
                    title: '<span class="icon icon-droplet"></span> geen knop weergave',
                    'class': '',
                    tags: ['a'],
                },
            ],
        };
    </script>
@endpush

<script>
    // Load redactor for all data-editor instances
    $R('[data-editor]');
</script>