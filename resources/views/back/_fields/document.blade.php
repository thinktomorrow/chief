<?php
    $files = $manager->fieldValue($field, $locale ?? null);
    $name = $name ?? $field->name();
?>

@foreach($files as $document)
    <div class="border border-grey-100 rounded inset-s stack-s center-y bg-white" id="asset-{{$document->id}}">
        <div>
            <strong>{{ $document->getFilename() }}</strong>
            <br>
            <span class="text-subtle">
                {{ $document->getExtensionType() }} | {{ $document->getSize() }}
            </span>
        </div>

        <div style="margin-left:auto;" class="pr-2">
            <a href="{{ url($document->getFileUrl()) }}" target="_blank">Bekijk document</a>
        </div>
        <div>
            <svg onclick="removeFile({{$document->id}})" width="18" height="18"><use xlink:href="#x"/></svg>
        </div>
    </div>
    <input type="hidden" id="removeFile-{{$document->id}}" name="{{ $name }}[delete][]" {{ $field->multiple ? 'multiple' : '' }}/>
@endforeach

<label for="">Voeg document toe:</label>
<input type="file" name="{{ $name }}[new][]" {{ $field->multiple ? 'multiple' : '' }} style="opacity:1; position:static;"/>

@push('custom-scripts')
<script>
    function removeFile(id)
    {
        document.getElementById('removeFile-'+id).value = id;
        document.getElementById('asset-'+id).remove();
    }
</script>
@endpush
