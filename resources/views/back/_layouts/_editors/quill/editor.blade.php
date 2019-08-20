@push('custom-scripts')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endpush

<style>
    .ql-container, .ql-editor {
        height: auto;
        min-height: 8rem;
    }
</style>

<script>
    var editors = document.querySelectorAll('[data-editor]');
    for(var i = 0; i < editors.length; i++) {
        var quill = new Quill(editors[i], {
            theme: 'snow'
        });
        quill.on('text-change', function() {
            var editorInput = document.getElementsByName(quill.container.id)[0];
            editorInput.value = quill.root.innerHTML;
        });
    }   
</script>