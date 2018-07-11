<section class="formgroup">
    <div class="row gutter-xl">
        <div class="formgroup-info column-4">
            <h2 class="formgroup-label">{{ $label ?? 'Media' }}</h2>
            <p>{{ $description ?? '' }}</p>
        </div>
        <div class="column-8">
            <filesupload group="{{ $group }}" v-cloak preselected="{{ isset($files) ? json_encode($files) : '[]'  }}" inline-template>
                <div id="filegroup-{{ $group }}" :class="{'sorting-mode' : reorder}">
                    <div class="row gutter-s">
                        <div v-for="item in items" class="column-3 draggable-item" :draggable="reorder" :data-item-id="item.id"
                        @dragstart="handleSortingStart"
                        @dragenter.prevent="handleSortingEnter">
                        <slim group="{{ $group }}" :options="{
                            id: item.id,
                            filename: item.filename,
                            url:item.url,
                            file: item.file,
                            label: 'Drop hier uw afbeelding',
                        }"></slim>
                    </div>

                    <div class="column-3">
                        <div class="thumb thumb-new" id="file-drop-area-{{ $group }}"
                             :class="{ 'is-dropped' : isDropped, 'is-dragging-over' : isDraggingOver }"
                             @dragover.prevent="handleDraggingOver"
                             @dragleave.prevent="handleDraggingLeave"
                             @drop.prevent="handleDrop">
                            <!-- allow to click for upload -->
                            <input v-if="checkSupport" type="file" @change="handleFileSelect" multiple accept="image/*"/>
                            <!-- if not supported, a file can still be passed along -->
                            <input v-else type="file" name="files[{{ $group }}][]" multiple accept="image/*"/>
                            <span class="icon icon-plus"></span>
                        </div>
                    </div>
                </div>
                <a class="btn btn-subtle" @click.prevent="toggleReorder">
                    @{{ reorder ? 'Gedaan met herschikken' : 'Herschik afbeeldingen' }}
                </a>
                <input type="hidden" name="filesOrder[{{ $group }}]" :value="filesOrder">
            </filesupload>
        </div>
    </div>
</section>