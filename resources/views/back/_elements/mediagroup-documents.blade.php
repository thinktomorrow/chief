<section class="formgroup">
    <div class="row gutter-xl">
        <div class="formgroup-info column-4">
            <h2 class="formgroup-label">{{ $label ?? 'Media' }}</h2>
            <p>{!! $description ?? '' !!}</p>
        </div>
        <div class="column-8">
            @foreach($page->getAllFiles(\Thinktomorrow\Chief\Media\MediaType::DOCUMENT) as $document)
                <div class="panel panel-default inset-s stack-s center-y bg-white" id="asset-{{$document->id}}">
                    <div>
                        <strong>{{ $document->getFilename() }}</strong>
                        <br>
                        <span class="text-subtle">
                            {{ $document->getExtensionType() }} | {{ $document->getSize() }}
                        </span>
                    </div>

                    <div style="margin-left:auto;">
                        <a href="{{ url($document->getFileUrl()) }}" target="_blank">Bekijk document</a>
                    </div>
                    <div>
                        <span class="icon-x" onclick="removeFile({{$document->id}})"></span>
                    </div>
                </div>
            @endforeach
            <label for="">Voeg document toe:</label>
            <input type="file" name="files[{{ $group }}][new][]" multiple style="opacity:1; position:static;"/>
            <input type="hidden" id="removeFile" name="files[{{ $group }}][delete][]" multiple/>
        </div>
    </div>
</section>
@push('custom-scripts')
    <script>
        function removeFile(id){
            document.getElementById('removeFile').value = id;
            document.getElementById("asset-"+id).remove();
        }
    </script>
@endpush