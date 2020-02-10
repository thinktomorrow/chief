<?php
$files = $manager->fieldValue($field, $locale ?? null);
$name = $name ?? $field->getName();
$locale = $locale ?? app()->getLocale();
?>

@foreach($files as $file)

    <div class="border border-grey-100 rounded inset-s stack-s center-y bg-white" id="asset-{{$file->id}}">

        <div>
            <strong>{{ $file->filename() }}</strong>
            <br>
            <span class="text-grey-300">
                {{ $file->getExtensionType() }} | {{ $file->getSize() }}
            </span>
        </div>

        <div class="pr-2 ml-auto">
            <a href="{{ url($file->url()) }}" target="_blank">Bekijk document</a>
        </div>

        <div>
            <svg onclick="removeFile({{$file->id}}, '{{$locale}}')" width="18" height="18"><use xlink:href="#x"/></svg>
        </div>

    </div>

    <input type="hidden" id="removeFile-{{$file->id}}" name="{{ $name }}[delete][]" {{ $field->allowMultiple() ? 'multiple' : '' }}/>
@endforeach

<div data-document-upload data-locale="{{ $locale }}" class="{{ !empty($files) && !$field->multiple ? 'hidden' : ''}}">
    <label for="document-upload-{{$locale}}" class="btn btn-secondary mr-4">
        Document uploaden
    </label>
    <span class="text-secondary-500"></span>
</div>
<input id="document-upload-{{$locale}}" onchange="inputValueToLabel(event, '{{$locale}}')" type="file" name="{{ $name }}[new][]" {{ $field->allowMultiple() ? 'multiple' : '' }} class="hidden">

@push('custom-scripts')
    <script>
        function removeFile(id, locale) {
            document.getElementById('removeFile-'+id).value = id;
            document.getElementById('asset-'+id).remove();
            document.querySelector("[data-document-upload][data-locale='" + locale + "']").classList.remove('hidden');
        }

        function inputValueToLabel(event, locale) {
            var selector = document.querySelector("[data-document-upload][data-locale='" + locale + "']");

            var fileName = selector.getElementsByTagName('span')[0],
                label = selector.getElementsByTagName('label')[0],
                valuePathArray = event.target.value.split('\\'),
                value = valuePathArray[valuePathArray.length - 1];

            fileName.innerHTML = value;
            label.innerHTML = event.target.value === '' ? label.innerHTML : 'Een ander document uploaden';
        }
    </script>
@endpush
