<?php
    // TODO: this should be optimized performance wise since we fetch every file every time...
    $files = $manager->getFieldValue($field);
    if(isset($name))
    {
        $files = $files[substr($key, 9)] ?? [];
    }
    $name = $name ?? $key;
?>

<filesupload group="{{ $key }}" v-cloak preselected="{{ count($files) ? json_encode($files) : '[]'  }}" inline-template>
    <div id="filegroup-{{ $key }}" :class="{'sorting-mode' : reorder}">
        <div class="row gutter-s">
            <div v-for="item in items" class="column-3 draggable-item" :draggable="reorder" :data-item-id="item.id"
                 @dragstart="handleSortingStart"
                 @dragenter.prevent="handleSortingEnter">
                <slim group="{{ $key }}" :options="{
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
                    <input v-else type="file" name="files[{{ $key }}][]" {{ $field->multiple ? 'multiple' : '' }} accept="image/*"/>
                    <span class="icon icon-plus"></span>
                </div>
            </div>
        </div>
        <a v-if="{{ json_encode($field->multiple) }} == true" class="btn btn-subtle" @click.prevent="toggleReorder">
            @{{ reorder ? 'Gedaan met herschikken' : 'Herschik afbeeldingen' }}
        </a>
        <input type="hidden" name="filesOrder[{{ $key }}]" :value="filesOrder">
    </div>
</filesupload>
