<?php
    $files = $manager->fieldValue($field, $locale ?? null);
    $name = $name ?? $field->name();
?>

@foreach($files as $document)

    <div class="border border-grey-100 rounded inset-s stack-s center-y bg-white" id="asset-{{$document->id}}">

        <div>
            <strong>{{ $document->getFilename() }}</strong>
            <br>
            <span class="text-grey-300">
                {{ $document->getExtensionType() }} | {{ $document->getSize() }}
            </span>
        </div>

        <div class="pr-2 ml-auto">
            <a href="{{ url($document->getFileUrl()) }}" target="_blank">Bekijk document</a>
        </div>

        <div>
            <svg onclick="removeFile({{$document->id}})" width="18" height="18"><use xlink:href="#x"/></svg>
        </div>

    </div>

    <input type="hidden" id="removeFile-{{$document->id}}" name="{{ $name }}[delete][]" {{ $field->multiple ? 'multiple' : '' }}/>

@endforeach

<div data-document-upload>
    <label for="document-upload" class="btn btn-secondary mr-4">
        Document uploaden
    </label>
    <span class="text-secondary-500"></span>
</div>
<input id="document-upload" onchange="inputValueToLabel(event)" type="file" name="{{ $name }}[new][]" {{ $field->multiple ? 'multiple' : '' }} class="hidden">

@push('custom-scripts')
    <script>

        function removeFile(id)
        {
            document.getElementById('removeFile-'+id).value = id;
            document.getElementById('asset-'+id).remove();
        }

        function inputValueToLabel(e) {
            var fileName = document.querySelector('[data-document-upload]').getElementsByTagName('span')[0],
                label = document.querySelector('[data-document-upload]').getElementsByTagName('label')[0],
                valuePathArray = e.target.value.split('\\'),
                value = valuePathArray[valuePathArray.length - 1];

            fileName.innerHTML = value;
            label.innerHTML = e.target.value === "" ? label.innerHTML : "Een ander document uploaden";
        }

    </script>
@endpush

