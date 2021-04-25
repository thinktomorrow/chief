<?php
    $files = $field->getValue($locale ?? null);
    $locale = $locale ?? app()->getLocale();
    $name = $name ?? $field->getName($locale);

    $imagesUploadSettings = json_encode([
        'group'      => $field->getKey(),
        'locale'     => $locale,
        'name'       => $name,
        'multiple'   => $field->allowMultiple(),
    ]);
?>
<imagesupload reference="{{ \Illuminate\Support\Str::random(10) }}" :settings="{{ $imagesUploadSettings }}" v-cloak preselected="{{ count($files) ? json_encode($files) : '[]'  }}">
    <div slot-scope="{items, sort, drag, settings, gallery, reference}"  :id="'filegroup-' + reference + '-' + settings.locale" :class="{'sorting-mode' : sort.isReordering}">
        <div class="row gutter-s">
            <div v-for="(item, index) in items" :key="item.key" class="column-4 draggable-item" :draggable="sort.isReordering" :data-item-id="item.id"
                 @dragstart="sort.handleSortingStart"
                 @dragenter.prevent="sort.handleSortingEnter">
                    <image-component :item="item" @input="(prop, value) => { items[index][prop] = value; }" :name="settings.name" :group="settings.group" upload-url="@adminRoute('asyncUploadSlimImage',$field->getKey(), $model ? $model->id : null)">
                        <div class="thumb" slot-scope="{hiddenInputName, hiddenInputValue, name}">
                            <div>
                                <img v-show="item.url" :src="item.url" :alt="item.filename">
                                <input style="margin-bottom:0;" type="hidden" :name="hiddenInputName" :value="hiddenInputValue" />
                                <input style="margin-bottom:0;" type="file" :name="name+'[]'" accept="image/jpeg, image/png, image/svg+xml, image/webp" />
                            </div>
                        </div>
                    </image-component>
            </div>
            <div v-if="settings.multiple == true || items.length < 1">
                <div class="thumb thumb-new" :id="'file-drop-area-' + settings.group"
                     :class="{ 'is-dropped' : drag.isDropped, 'is-dragging-over' : drag.isDraggingOver }"
                     @dragover.prevent="drag.handleDraggingOver"
                     @dragleave.prevent="drag.handleDraggingLeave"
                     @drop.prevent="drag.handleDrop">
                    <!-- allow to click for upload -->
                    <input v-if="drag.isSupported" type="file" @change="drag.handleFileSelect" {{ $field->allowMultiple() ? 'multiple' : '' }} accept="image/jpeg, image/png, image/svg+xml, image/webp"/>
                    <!-- if not supported, a file can still be passed along -->
                    <input v-else type="file" name="{{ $name }}[]" {{ $field->allowMultiple() ? 'multiple' : '' }} accept="image/jpeg, image/png, image/svg+xml, image/webp"/>
                    <span><svg width="18" height="18"><use xlink:href="#plus"/></svg></span>
                </div>
            </div>
        </div>

        <div class="flex mt-4">
            <div v-if="(settings.multiple == true || items.length < 1 || !drag.hasValidUpload) && !sort.isReordering">
                <div class="btn btn-primary-outline mr-4" @click="gallery.open">
                    Voeg bestaande toe uit je galerij
                </div>

                <mediagallery url="{{ route('chief.api.media') }}" :reference="reference" :locale="settings.locale" :uploaded="items.map(o=>o.id)" :multiple="{{ $field->allowMultiple() ? 'true' : 'false' }}"></mediagallery>
            </div>

            <a v-if="settings.multiple == true  && items.length > 1" @click.prevent="sort.toggleReorder" class="btn btn-primary">
                @{{ sort.isReordering ? '&#10003; Gedaan met herschikken' : ' &#8644; Herschik afbeeldingen' }}
            </a>
            <input type="hidden" :name="'filesOrder['+ settings.locale +']['+ settings.group +']'" :value="sort.filesOrderInputValue">
        </div>
    </div>
</imagesupload>
