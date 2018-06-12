<script src="/chief-assets/back/js/vendors/redactor.js"></script>
<script>
    $R('[data-editor]', {
        plugins: ['redactorColumns', 'imagemanager', 'alignment'],
        @if(admin()->hasRole('developer'))
        buttons: ['html', 'format', 'bold', 'italic', 'lists', 'image', 'file', 'link'],
        @else
        buttons: ['format', 'bold', 'italic', 'lists', 'image', 'file', 'link'],
        @endif
        formatting: ['p', 'h2', 'h3'],
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
        imageUpload: '{{ route('pages.media.upload', $page->id) }}',
    });
</script>