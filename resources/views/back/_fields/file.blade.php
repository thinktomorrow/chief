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
                        isImage: item.isImage,
                        thumbUrl: item.thumbUrl,
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
                        {!! $field->getUploadButtonLabel() !!}
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
