<?php
    $files = $manager->fieldValue($field, $locale ?? null);
    $locale = $locale ?? app()->getLocale();
    $name = $name ?? $field->translateName($locale);
    $slug = $field->sluggifyName();
?>

<filesupload group="{{ $slug }}" locale="{{ $locale }}" v-cloak preselected="{{ count($files) ? json_encode($files) : '[]'  }}" inline-template>
    <div id="filegroup-{{ $slug }}-{{$locale}}" :class="{'sorting-mode' : reorder}">
        <div class="row gutter-s">
            <div v-for="(item, index) in items" class="column-3 draggable-item" :draggable="reorder" :data-item-id="item.id"
                 @dragstart="handleSortingStart"
                 @dragenter.prevent="handleSortingEnter" v-show="!item.deleted || ({{ json_encode($field->multiple) }} != true && !hasValidUpload && index == 0)">
                    <slim name="{{ $name }}" group="{{ $slug }}" :options="{
                        id: item.id,
                        filename: item.filename,
                        url: item.url,
                        file: item.file,
                        label: 'Drop hier uw afbeelding',
                        newUpload: item.newUpload,
                    }"></slim>
            </div>
            <div v-if="{{ json_encode($field->multiple) }} == true || items.length < 1">
                <div class="thumb thumb-new" id="file-drop-area-{{ $slug }}"
                     :class="{ 'is-dropped' : isDropped, 'is-dragging-over' : isDraggingOver }"
                     @dragover.prevent="handleDraggingOver"
                     @dragleave.prevent="handleDraggingLeave"
                     @drop.prevent="handleDrop">
                    <!-- allow to click for upload -->
                    <input v-if="checkSupport" type="file" @change="handleFileSelect" {{ $field->multiple ? 'multiple' : '' }} accept="image/*"/>
                    <!-- if not supported, a file can still be passed along -->
                    <input v-else type="file" name="{{ $name }}[]" {{ $field->multiple ? 'multiple' : '' }} accept="image/*"/>
                    <span><svg width="18" height="18"><use xlink:href="#plus"/></svg></span>
                </div>
            </div>
        </div>

        <div v-if="{{ json_encode($field->multiple) }} == true || items.length < 1 || !hasValidUpload">
            <div class="btn btn-link" onClick="window.showModal('mediagallery-{{ $slug }}-{{$locale}}')">
                <span>Voeg bestaande toe uit je galerij</span>
            </div>
            <mediagallery group="{{ $slug }}" locale="{{$locale}}"></mediagallery>
        </div>

        <a v-if="{{ json_encode($field->multiple) }} == true" @click.prevent="toggleReorder" class="btn btn-link">
            @{{ reorder ? '&#10003; Gedaan met herschikken' : ' &#8644; Herschik afbeeldingen' }}
        </a>
        <input type="hidden" name="filesOrder[{{ $locale }}][{{ $slug }}]" :value="filesOrder">
    </div>
</filesupload>
