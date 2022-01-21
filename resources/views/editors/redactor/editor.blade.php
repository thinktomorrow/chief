@push('custom-scripts')
    <script>
        $R.options = {
            plugins: ['redactorColumns', 'imagemanager', 'alignment', 'rich-links', 'custom-classes', 'video', 'clips'],

            buttons: ['html', 'undo', 'format', 'bold', 'italic', 'lists', 'image', 'file', 'link'],

            formatting: ['h2', 'h3', 'p', 'blockquote'],
            imageResizable: true,
            lang: 'nl',
            imagePosition: true,
            imageFigure: false,
{{--            @if( ! \Thinktomorrow\Chief\Snippets\SnippetCollection::appearsEmpty())--}}
{{--                clips: @json(\Thinktomorrow\Chief\Snippets\SnippetCollection::load()->toClips()),--}}
{{--            @endif--}}
            @if(isset($imageUploadUrl) && (!isset($disableImageUpload) || !$disableImageUpload))
                imageUpload: chiefRedactorImageUpload('{{ $imageUploadUrl }}'),
            @endif
            definedlinks: '{{ route('chief.api.internal-links') }}',
            mediagalleryApi: '{{ route('chief.api.media')}}',
            customClasses: [
                {
                    title: '<span><svg width="18" height="18"><use xlink:href="#button"/></svg></span> link als knop',
                    'class': 'btn btn-default',
                    tags: ['a'],
                },
                {
                    title: '<span><svg width="18" height="18"><use xlink:href="#button"/></svg></span> link als primaire knop',
                    'class': 'btn btn-primary',
                    tags: ['a'],
                },
                {
                    title: '<span><svg width="18" height="18"><use xlink:href="#button"/></svg></span> link as secundaire knop',
                    'class': 'btn btn-primary-outline',
                    tags: ['a'],
                },
                {
                    title: '<span><svg width="18" height="18"><use xlink:href="#button"/></svg></span> geen knop weergave',
                    'class': '',
                    tags: ['a'],
                },
            ],
            toolbarFixedTopOffset: 80, // Fixed chief header compensation
        };
    </script>

@endpush

<script>
    // Load redactor for all data-editor instances
    $R('[data-editor]');
</script>
