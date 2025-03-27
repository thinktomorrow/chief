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
            definedlinks: '{{ route('chief.api.internal-links') }}',
            customClasses: [
                { title: 'link als knop', class: 'btn btn-default', tags: ['a'] },
                { title: 'link als primaire knop', class: 'btn btn-primary', tags: ['a'] },
                { title: 'link as secundaire knop', class: 'btn btn-primary-outline', tags: ['a'] },
                { title: 'geen knop weergave', class: '', tags: ['a'] },
            ],
            toolbarFixedTopOffset: 80,
        };

        // Load redactor for all data-editor instances
        $R('[data-editor]');

        // Init redactor in livewire dialogs
        document.addEventListener('fragment-dialog-opened', () => {
            // Next tick my friend... next tick
            setTimeout(() => {
                $R('[data-editor]');
            }, 0);
        });
    </script>
@endpush
