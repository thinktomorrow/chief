<?php
    /** @var \Thinktomorrow\AssetLibrary\Asset[] $files */
    $files = $field->getValue($locale ?? null);
    $locale = $locale ?? app()->getLocale();
    $name = $name ?? $field->getName($locale);
    $slug = $field->getKey();
?>

<filesupload group="{{ $slug }}" locale="{{ $locale }}" v-cloak preselected="{{ count($files) ? json_encode($files) : '[]'  }}" inline-template>
    <div id="filegroup-{{ $slug }}-{{$locale}}" :class="{'sorting-mode' : reorder}">
        <div class="row gutter-s">
            <div v-for="(item, index) in items" class="column-12 draggable-item" :draggable="reorder" :data-item-id="item.id"
                 @dragstart="handleSortingStart"
                 @dragenter.prevent="handleSortingEnter" v-show="!item.deleted || ({{ json_encode($field->allowMultiple()) }} != true && !hasValidUpload && index == 0)">
                <file name="{{ $name }}" group="{{ $slug }}" locale="{{$locale}}" :options="{
                        id: item.id,
                        filename: item.filename,
                        url: item.url,
                        mimetype: item.mimetype || null,
                        size: item.size || null,
                        file: item.file,
                        addedFromGallery: item.addedFromGallery,
                    }"></file>
            </div>
        </div>
        <div class="flex mt-4">
            <div v-if="{{ json_encode($field->allowMultiple()) }} == true || items.length < 1">

                <div data-document-upload data-locale="{{ $locale }}">
                    <label for="document-upload-{{$locale}}" class="btn btn-secondary mr-4">
                        Document uploaden
                    </label>
                    <span class="text-secondary-500"></span>
                </div>
                <input id="document-upload-{{$locale}}" @change="handleFileSelect" type="file" {{ $field->allowMultiple() ? 'multiple' : '' }} class="hidden">
            </div>
            <a v-if="{{ json_encode($field->allowMultiple()) }} == true && items.length > 1" @click.prevent="toggleReorder" class="btn btn-primary">
                @{{ reorder ? '&#10003; Gedaan met herschikken' : ' &#8644; Herschik bestanden' }}
            </a>
            <input type="hidden" name="filesOrder[{{ $locale }}][{{ $slug }}]" :value="filesOrder">
        </div>
    </div>
</filesupload>



{{--<div data-document-upload data-locale="{{ $locale }}" class="{{ !empty($files) && !$field->allowMultiple() ? 'hidden' : ''}}">--}}
{{--    <label for="document-upload-{{$locale}}" class="btn btn-secondary mr-4">--}}
{{--        Document uploaden--}}
{{--    </label>--}}
{{--    <span class="text-secondary-500"></span>--}}
{{--</div>--}}
{{--<input id="document-upload-{{$locale}}" onchange="inputValueToLabel(event, '{{$locale}}')" type="file" name="{{ $name }}[]" {{ $field->allowMultiple() ? 'multiple' : '' }} class="hidden">--}}

{{--@push('custom-scripts')--}}
{{--    <script>--}}
{{--        function removeFile(id, locale) {--}}
{{--            document.getElementById('removeFile-'+id).value = id;--}}
{{--            document.getElementById('asset-'+id).remove();--}}
{{--            document.querySelector("[data-document-upload][data-locale='" + locale + "']").classList.remove('hidden');--}}

{{--            // Remove entry in replace array as well.--}}
{{--            document.getElementById("existingFile-"+id+"-"+locale).remove();--}}
{{--        }--}}

{{--        function inputValueToLabel(event, locale) {--}}
{{--            var selector = document.querySelector("[data-document-upload][data-locale='" + locale + "']");--}}

{{--            var fileName = selector.getElementsByTagName('span')[0],--}}
{{--                label = selector.getElementsByTagName('label')[0],--}}
{{--                valuePathArray = event.target.value.split('\\'),--}}
{{--                value = valuePathArray[valuePathArray.length - 1];--}}

{{--            fileName.innerHTML = value;--}}
{{--            label.innerHTML = event.target.value === '' ? label.innerHTML : 'Een ander document uploaden';--}}
{{--        }--}}
{{--    </script>--}}
{{--@endpush--}}
