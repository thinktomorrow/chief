<?php
    $files = $manager->fieldValue($field, $locale ?? null);
    $locale = $locale ?? app()->getLocale();
    $name = $name ?? $field->getName($locale);
    $slug = $field->getKey();
?>

@push('custom-styles')
    <link rel="stylesheet" href="{{ asset('/assets/back/css/vendor/slim.min.css') }}">
    <style type="text/css">

        .slim{
            max-height: 250px;
        }

        .slim-error{
            min-height:80px;
        }

        .slim .slim-area .slim-upload-status[data-state=error] {
            right: .5em;
            left: .5em;
            line-height: 1.1;
            padding: .3em;
            white-space: normal;
        }

        .slim .slim-area .slim-result img{
            height: 100%;
            object-fit: cover;
        }

        .thumb [data-state=empty] {
            height: 80px;
        }
    </style>
@endpush
@push('custom-scripts')
    <script src="{{ asset('/assets/back/js/vendor/slim.min.js') }}"></script>
@endpush

<imagesupload group="{{ $slug }}" locale="{{ $locale }}" v-cloak preselected="{{ count($files) ? json_encode($files) : '[]'  }}" inline-template>
    <div id="filegroup-{{ $slug }}-{{$locale}}" :class="{'sorting-mode' : reorder}">
        <div class="row gutter-s">
            <div v-for="(item, index) in items" class="column-4 draggable-item" :draggable="reorder" :data-item-id="item.id"
                 @dragstart="handleSortingStart"
                 @dragenter.prevent="handleSortingEnter"
                 v-show="!item.deleted"
            >
                    <image-component :item="item" :key="index" @input="(newItem) => { items[index] = newItem; }" name="{{ $name }}" group="{{ $slug }}">
                        <div class="thumb" slot-scope="{hiddenInputName, hiddenInputValue, name}">
                            <div>
                                <img v-show="item.url" :src="item.url" :alt="item.filename">
                                <input style="margin-bottom:0;" type="hidden" :name="hiddenInputName" :value="hiddenInputValue" />
                                <input style="margin-bottom:0;" type="file" :name="name+'[]'" accept="image/jpeg, image/png, image/svg+xml, image/webp" />
                            </div>
                        </div>
                    </image-component>
            </div>
            <div v-if="{{ json_encode($field->allowMultiple()) }} == true || items.length < 1">
                <div class="thumb thumb-new" id="file-drop-area-{{ $slug }}"
                     :class="{ 'is-dropped' : isDropped, 'is-dragging-over' : isDraggingOver }"
                     @dragover.prevent="handleDraggingOver"
                     @dragleave.prevent="handleDraggingLeave"
                     @drop.prevent="handleDrop">
                    <!-- allow to click for upload -->
                    <input v-if="checkSupport" type="file" @change="handleFileSelect" {{ $field->allowMultiple() ? 'multiple' : '' }} accept="image/jpeg, image/png, image/svg+xml, image/webp"/>
                    <!-- if not supported, a file can still be passed along -->
                    <input v-else type="file" name="{{ $name }}[]" {{ $field->allowMultiple() ? 'multiple' : '' }} accept="image/jpeg, image/png, image/svg+xml, image/webp"/>
                    <span><svg width="18" height="18"><use xlink:href="#plus"/></svg></span>
                </div>
            </div>
        </div>

        <div class="flex mt-4">
            <div v-if="({{ json_encode($field->allowMultiple()) }} == true || items.length < 1 || !hasValidUpload) && !reorder">
                <div class="btn btn-primary mr-4" onClick="window.showModal('mediagallery-{{ $slug }}-{{$locale}}')">
                    <span>Voeg bestaande toe uit je galerij</span>
                </div>
                <mediagallery group="{{ $slug }}" locale="{{$locale}}" :uploaded="items.map(o=>o.id)"></mediagallery>
            </div>

            <a v-if="{{ json_encode($field->allowMultiple()) }} == true  && items.length > 1" @click.prevent="toggleReorder" class="btn btn-primary">
                @{{ reorder ? '&#10003; Gedaan met herschikken' : ' &#8644; Herschik afbeeldingen' }}
            </a>
            <input type="hidden" name="filesOrder[{{ $locale }}][{{ $slug }}]" :value="filesOrder">
        </div>
    </div>
</imagesupload>
