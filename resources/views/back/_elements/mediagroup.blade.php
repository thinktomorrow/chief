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
                            <input v-if="checkSupport" type="file" @change="handleFileSelect" multiple/>
                            <!-- if not supported, a file can still be passed along -->
                            <input v-else type="file" name="files[]" multiple/>
                            <span class="icon icon-plus"></span>
                        </div>
                    </div>
                </div>
                <a class="btn btn-subtle" @click.prevent="toggleReorder">
                    @{{ reorder ? 'Gedaan met herschikken' : 'Herschik afbeeldingen' }}
                </a>
                <input type="hidden" name="filesOrder" :value="filesOrder">
        </div>
        </filesupload>
        </div>
    </div>
</section>

@push('custom-styles')
<link rel="stylesheet" href="{{ cached_asset('/chief-assets/back/css/vendors/slim.min.css','back') }}">
@endpush
@push('custom-scripts')
<script src="{{ cached_asset('/chief-assets/back/js/vendors/slim.kickstart.min.js','back') }}"></script>
<script>

    Vue.component('slim', {
        props: ['options', 'group'],
        template: `
                <div class="thumb">
                    <div class="slim">
                        <img v-if="url" :src="url" :alt="filename">
                        <input v-if="id" type="file" :name="'files['+group+'][replace]['+id+']'" />
                        <input v-else type="file" :name="'files['+group+'][new][]'" />
                    </div>
                    <input v-if="deletion" type="hidden" :name="'files['+group+'][delete][]'" :value="id"/>
                </div>
            `,
        data: function () {
            return {
                id: this.options.id || null,
                url: this.options.url || null, // Already existing link
                file: this.options.file || null, // Newly created file object
                filename: this.options.filename || null,
                instance: null,
                deletion: false,
            }
        },
        mounted: function () {

            this.options.didRemove = this.markForDeletion;
            this.options.didLoad = this.onLoad;

            this.instance = new Slim(this.$el.childNodes[0], this.options);

            // If a file instance is passed, we want to directly load the file into our cropper
            if (this.file) this.instance.load(this.file);
        },
        methods: {
            markForDeletion: function (e) {
                // If event contains a reference to an image,
                // this is a replace action and not a delete one
                this.deletion = true;
            },
            onLoad: function () {

                // Unmark for deletion
                this.deletion = false;

                Eventbus.$emit('files-loaded-' + this.group,{});

                // Let Slim know it's good to go on - didLoad callback allows for input check prior to Slim.
                return true;
            },
        },
    });

    Vue.component('filesupload', {
        props: ['preselected', 'group'],
        data: function () {
            return {
                isDraggingOver: false,
                isDropped: false,

                items: this.preselected ? JSON.parse(this.preselected) : [],

                fileDropArea: null,
                fileInput: null,
                fileInputName: null,

                // Sorting
                filesOrder: [],
                reorder: false,
                sortSource: null,
            };
        },
        created: function () {
            this.fileDropArea = document.querySelector('#file-drop-area-' + this.group);
            this.fileInput = this.fileDropArea.querySelector('input');
            this.fileInputName = this.fileInput.name;

            /**
             * When a new image is loaded, we want to reorder our files so
             * this new one is included in the list. We have a small delay
             * to assert the slim loading event has finished.
             */
            var self = this;
            Eventbus.$on('files-loaded-' + this.group, function () {
                setTimeout(function () {
                    self.updateFilesOrder();
                }, 1500);
            });

        },
        mounted: function () {
            this.updateFilesOrder();
        },
        methods: {
            handleDraggingOver: function () {
                this.isDraggingOver = true;
            },
            handleDraggingLeave: function () {
                this.isDraggingOver = false;
            },
            handleDrop: function (e) {
                this.isDraggingOver = false;
                this.isDropped = true;

                this._handleFileItems(e.dataTransfer.items || e.dataTransfer.files);
                this.isDropped = false;
            },
            handleFileSelect: function (e) {
                this._handleFileItems(e.target.files);
            },
            _handleFileItems: function (items) {
                var l = items.length;
                for (var i = 0; i < l; i++) {
                    this._handleItem(items[i]);
                }
            },
            _handleItem: function (item) {
                var file = item;
                if (item.getAsFile && item.kind == 'file') {
                    file = item.getAsFile();
                }

                this.items.push({file: file});
            },
            checkSupport: function () {
                var div = document.createElement('div');
                var supportDraggable = ('draggable' in div) || ('ondragstart' in div && 'ondrop' in div);
                var supportUpload = 'FormData' in window && 'FileReader' in window;

                return supportDraggable && supportUpload;
            },

            /**
             * Sorting methods
             */
            updateFilesOrder: function () {
                this.filesOrder = [];

                var draggableItems = document.querySelectorAll('#filegroup-'+ this.group +' .draggable-item');

                for (var i = 0; i < draggableItems.length; i++) {
                    var itemId = draggableItems[i].getAttribute('data-item-id');

                    // Newly added items do not have an id yet, so the filename is passed
                    // to we are still able to identify their order upon saving.
                    if (!itemId) {
                        var input = draggableItems[i].querySelector('input[name="files['+this.group+'][new][]"]');

                        // Possible reason is that loading of new file takes too long and we tried
                        // to sort the files before it finished loading
                        if (!input || !input.value) return;

                        var slimValues = JSON.parse(input.value);
                        itemId = slimValues.input.name;
                    }

                    this.filesOrder.push(itemId);
                }
            },
            toggleReorder: function () {
                this.reorder = !this.reorder;
            },

            handleSortingStart: function (e) {

                this.sortSource = e.target;
                this.sortSource.style.opacity = '0.4';

                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);// FF support

            },
            handleSortingEnter: function (e) {

                this.sortSource.style.opacity = '1';

                // We need our draggable-item, not the child elements
                var target = e.target.classList.contains('draggable-item') ? e.target : this.findAncestor(e.target, '.draggable-item');

                if (target && this.isbefore(this.sortSource, target)) {
                    target.parentNode.insertBefore(this.sortSource, target);
                    this.updateFilesOrder();
                }
                else {
                    if (!target || this.sortSource == target.nextSibling) {
                        return;
                    }

                    target.parentNode.insertBefore(this.sortSource, target.nextSibling);
                    this.updateFilesOrder();
                }
            },

            isbefore: function (a, b) {
                if (a.parentNode == b.parentNode) {
                    for (var cur = a; cur; cur = cur.previousSibling) {
                        if (cur === b) {
                            return true;
                        }
                    }
                }
                return false;
            },

            findAncestor: function (el, sel) {
                while ((el = el.parentElement) && !((el.matches || el.matchesSelector).call(el, sel)));
                return el;
            },
        },
    });
</script>

@endpush