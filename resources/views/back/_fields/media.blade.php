<?php
    $files = $manager->fieldValue($field, $locale ?? null);
    $name = $name ?? $field->name();
?>

<filesupload group="{{ $name }}" v-cloak preselected="{{ count($files) ? json_encode($files) : '[]'  }}" inline-template>
    <div id="filegroup-{{ $name }}" :class="{'sorting-mode' : reorder}">
        <div class="row gutter-s">
            <div v-for="item in items" class="column-3 draggable-item" :draggable="reorder" :data-item-id="item.id"
                 @dragstart="handleSortingStart"
                 @dragenter.prevent="handleSortingEnter">
                <slim group="{{ 'files['. $name .']' }}" :options="{
                    id: item.id,
                    filename: item.filename,
                    url: item.url,
                    file: item.file,
                    label: 'Drop hier uw afbeelding',
                }"></slim>
            </div>
            <div v-if="{{ json_encode($field->multiple) }} == true || items.length < 1" class="column-3">
                <div class="thumb thumb-new" id="file-drop-area-{{ $name }}"
                     :class="{ 'is-dropped' : isDropped, 'is-dragging-over' : isDraggingOver }"
                     @dragover.prevent="handleDraggingOver"
                     @dragleave.prevent="handleDraggingLeave"
                     @drop.prevent="handleDrop">
                    <!-- allow to click for upload -->
                    <input v-if="checkSupport" type="file" @change="handleFileSelect" {{ $field->multiple ? 'multiple' : '' }} accept="image/*"/>
                    <!-- if not supported, a file can still be passed along -->
                    <input v-else type="file" name="{{ 'files['. $name .']' }}[]" {{ $field->multiple ? 'multiple' : '' }} accept="image/*"/>
                    <span><svg width="18" height="18"><use xlink:href="#plus"/></svg></span>
                </div>
            </div>
        </div>
        <a v-if="{{ json_encode($field->multiple) }} == true" @click.prevent="toggleReorder">
            @{{ reorder ? 'Gedaan met herschikken' : 'Herschik afbeeldingen' }}
        </a>
        <input type="hidden" name="filesOrder[{{ $key }}]" :value="filesOrder">
    </div>
</filesupload>
