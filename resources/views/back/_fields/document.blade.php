<?php
    $files = $manager->fieldValue($field, $locale ?? null);
    $name = $name ?? $field->name();
?>

@foreach($files as $document)

    <div class="border border-grey-100 rounded inset-s stack-s center-y bg-white" id="asset-{{$document->id}}">

        <div>
            <strong>{{ $document->filename() }}</strong>
            <br>
            <span class="text-grey-300">
                {{ $document->getExtensionType() }} | {{ $document->getSize() }}
            </span>
        </div>

        <div class="pr-2 ml-auto">
            <a href="{{ url($document->url()) }}" target="_blank">Bekijk document</a>
        </div>

        <div>
            <svg onclick="removeFile({{$document->id}})" width="18" height="18"><use xlink:href="#x"/></svg>
        </div>

    </div>

    <input type="hidden" id="removeFile-{{$document->id}}" name="{{ $name }}[delete][]" {{ $field->multiple ? 'multiple' : '' }}/>

@endforeach

<div data-document-upload data-locale="{{ $locale }}">
    <label for="document-upload-{{$locale}}" class="btn btn-secondary mr-4">
        Document uploaden
    </label>
    <span class="text-secondary-500"></span>
</div>
<input id="document-upload-{{$locale}}" onchange="
    (function(){
        var fileName = document.querySelector('[data-document-upload][data-locale=\'{{ $locale }}\']').getElementsByTagName('span')[0],
            label = document.querySelector('[data-document-upload][data-locale=\'{{ $locale }}\']').getElementsByTagName('label')[0],
            valuePathArray = event.target.value.split('\\'),
            value = valuePathArray[valuePathArray.length - 1];

        fileName.innerHTML = value;
        label.innerHTML = event.target.value === '' ? label.innerHTML : 'Een ander document uploaden';
    })();" 
    type="file" name="{{ $name }}[new][]" {{ $field->multiple ? 'multiple' : '' }} class="hidden">

@push('custom-scripts')
    <script>
        function removeFile(id) {
            document.getElementById('removeFile-'+id).value = id;
            document.getElementById('asset-'+id).remove();
        }
    </script>
@endpush
