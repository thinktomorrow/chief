<?php
    // TODO: this should be optimized performance wise since we fetch every file every time...
    $files = $manager->getFieldValue($field);
    $files = $files[$key] ?? [];
?>

@foreach($files as $document)
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
<input type="file" name="files[{{ $key }}][new][]" multiple style="opacity:1; position:static;"/>
<input type="hidden" id="removeFile" name="files[{{ $key }}][delete][]" multiple/>
