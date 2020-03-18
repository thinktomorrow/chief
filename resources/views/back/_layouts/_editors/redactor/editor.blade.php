@push('custom-scripts')

    <script>
        $R.options = {
            plugins: ['redactorColumns', 'imagemanager', 'alignment', 'rich-links', 'custom-classes', 'video', 'clips'],
            @if(chiefAdmin()->hasRole('developer'))
                buttons: ['html', 'format', 'bold', 'italic', 'lists', 'image', 'file', 'link'],
            @else
                buttons: ['undo', 'format', 'bold', 'italic', 'lists', 'image', 'file', 'link'],
            @endif
            formatting: ['h2', 'h3', 'p', 'blockquote'],
            imageResizable: true,
            lang: 'nl',
            imagePosition: true,
            imageFigure: false,
            @if( ! \Thinktomorrow\Chief\Snippets\SnippetCollection::appearsEmpty())
                clips: @json(\Thinktomorrow\Chief\Snippets\SnippetCollection::load()->toClips()),
            @endif
            @if(isset($imageUploadUrl) && (!isset($disableImageUpload) || !$disableImageUpload))
                imageUpload: chiefRedactorImageUpload('{{ $imageUploadUrl }}'),
            @endif
            definedlinks: '{{ route('chief.api.internal-links') }}',
            mediagalleryApi: '{{ route('chief.api.media')}}',
            customClasses: [
                {
                    title: '<span><svg width="18" height="18"><use xlink:href="#droplet"/></svg></span> link als knop',
                    'class': 'btn btn-default',
                    tags: ['a'],
                },
                {
                    title: '<span><svg width="18" height="18"><use xlink:href="#droplet"/></svg></span> link als primaire knop',
                    'class': 'btn btn-primary',
                    tags: ['a'],
                },
                {
                    title: '<span><svg width="18" height="18"><use xlink:href="#droplet"/></svg></span> link as secundaire knop',
                    'class': 'btn btn-secondary',
                    tags: ['a'],
                },
                {
                    title: '<span><svg width="18" height="18"><use xlink:href="#droplet"/></svg></span> geen knop weergave',
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
