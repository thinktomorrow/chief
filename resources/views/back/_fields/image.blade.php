<?php
    $files = $manager->fieldValue($field, $locale ?? null);
    $locale = $locale ?? app()->getLocale();
    $name = $name ?? $field->getName($locale);
    $slug = $field->getKey();
?>

<filesupload group="{{ $slug }}" locale="{{ $locale }}" v-cloak preselected="{{ count($files) ? json_encode($files) : '[]'  }}" inline-template async="/crazy-url">
    <div id="filegroup-{{ $slug }}-{{$locale}}" :class="{'sorting-mode' : reorder}">
        <div class="row gutter-s">
            <div v-for="(item, index) in items" class="column-3 draggable-item" :draggable="reorder" :data-item-id="item.id"
                 @dragstart="handleSortingStart"
                 @dragenter.prevent="handleSortingEnter" v-show="!item.deleted || ({{ json_encode($field->allowMultiple()) }} != true && !hasValidUpload && index == 0)">
                    <slim name="{{ $name }}" group="{{ $slug }}" :options="{
                        id: item.id,
                        filename: item.filename,
                        url: item.url,
                        file: item.file,
                        label: 'Drop hier uw afbeelding',
                        addedFromGallery: item.addedFromGallery,
                    }"></slim>
            </div>
            <div v-if="{{ json_encode($field->allowMultiple()) }} == true || items.length < 1">
                <div class="thumb thumb-new" id="file-drop-area-{{ $slug }}"
                     :class="{ 'is-dropped' : isDropped, 'is-dragging-over' : isDraggingOver }"
                     @dragover.prevent="handleDraggingOver"
                     @dragleave.prevent="handleDraggingLeave"
                     @drop.prevent="handleDrop">
                    <!-- allow to click for upload -->
                    <input v-if="checkSupport" type="file" @change="handleFileSelect" {{ $field->allowMultiple() ? 'multiple' : '' }} accept="image/*"/>
                    <!-- if not supported, a file can still be passed along -->
                    <input v-else type="file" name="{{ $name }}[]" {{ $field->allowMultiple() ? 'multiple' : '' }} accept="image/*"/>
                    <span><svg width="18" height="18"><use xlink:href="#plus"/></svg></span>
                </div>
            </div>
        </div>

        <div class="flex mt-4">
            <div v-if="{{ json_encode($field->allowMultiple()) }} == true || items.length < 1 || !hasValidUpload">
                <div class="btn btn-primary mr-4" onClick="window.showModal('mediagallery-{{ $slug }}-{{$locale}}')">
                    <span>Voeg bestaande toe uit je galerij</span>
                </div>
                <mediagallery group="{{ $slug }}" locale="{{$locale}}" :uploaded="items.map(o=>o.id)"></mediagallery>
            </div>

            <a v-if="{{ json_encode($field->allowMultiple()) }} == true" @click.prevent="toggleReorder" class="btn btn-primary">
                @{{ reorder ? '&#10003; Gedaan met herschikken' : ' &#8644; Herschik afbeeldingen' }}
            </a>
            <input type="hidden" name="filesOrder[{{ $locale }}][{{ $slug }}]" :value="filesOrder">
        </div>
    </div>
</filesupload>
