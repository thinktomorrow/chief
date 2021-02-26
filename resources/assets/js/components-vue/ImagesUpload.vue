<script>
    export default {
        props: ['reference', 'preselected', 'settings'],
        render(){
            return this.$scopedSlots.default({
                reference: this.reference,
                settings: this.settings,
                items: this.items,
                drag: {
                    isSupported: this.isSupported,
                    isDropped: this.isDropped,
                    isDraggingOver: this.isDraggingOver,
                    handleDraggingOver: this.handleDraggingOver,
                    handleDraggingLeave: this.handleDraggingLeave,
                    handleDrop: this.handleDrop,

                    handleFileSelect: this.handleFileSelect,
                    hasValidUpload: this.hasValidUpload,
                },
                sort: {
                    isReordering: this.isReordering,
                    toggleReorder: this.toggleReorder,
                    handleSortingStart: this.handleSortingStart,
                    handleSortingEnter: this.handleSortingEnter,
                    filesOrderInputValue: this.filesOrderInputValue,
                },
                gallery: {
                    open: this.openGalleryModal,
                }
            });
        },
        data: function () {
            return {
                isDraggingOver: false,
                isDropped: false,

                items: [],
                itemsUploading: [],

                fileDropArea: null,
                fileInput: null,
                fileInputName: null,

                isReordering: false,
                sortSource: null
            };
        },
        created: function () {

            var self = this;
            const preselection = this.preselected ? JSON.parse(this.preselected) : [];

            preselection.forEach((preselectedItem) => {
                self.addNewItem(preselectedItem.id, preselectedItem.filename, preselectedItem.url);
            });

            setTimeout(this.updateFilesOrder, 100);

            Eventbus.$on('mediagallery-loaded-' + this.reference, function (assets){
                assets.forEach(function(asset){
                    self.addNewItem(asset.id, asset.filename, asset.url);
                }, self)
                setTimeout(this.updateFilesOrder, 100);
            });

            Eventbus.$on('image-upload-request' + this.reference, (item) => {
                this.itemsUploading.push(item);
                if(this.itemsUploading.length == 1) {
                    Eventbus.$emit('disable-update-form');
                }
            });

            Eventbus.$on('image-upload-response' + this.reference, (item) => {
                this.itemsUploading.splice(this.itemsUploading.indexOf(item), 1);
                if(this.itemsUploading.length == 0) {
                    Eventbus.$emit('enable-update-form');
                }
            });
        },
        computed: {
            hasValidUpload: function(){
                var result = this.items.map(function(item){
                    return item.deleted;
                });
                return result.includes(undefined) || result.includes(false);
            },
            filesOrderInputValue: function(){
                return this.items.map(function(item){ return item.id;}).join(',');
            },
        },
        mounted: function () {
            this.updateFilesOrder();
        },
        methods: {
            openGalleryModal: function() {
                Eventbus.$emit('open-modal', 'mediagallery-' + this.reference + '-' + this.settings.locale);
            },
            addNewItem: function(id, filename, url, file){
                this.items.push({
                    key: 'key_' + this.randomString(10), // Internal key reference to satisfy vue loop key reference
                    id: id,
                    existingId: id,
                    filename: filename,
                    url: url,
                    deleted: false,
                    file: file, // original file object
                });
            },
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
                setTimeout(() => this.updateFilesOrder(), 1500);
            },
            _handleItem: function (item) {
                var file = item;

                if (item.getAsFile && item.kind == 'file') {
                    file = item.getAsFile();
                }

                this.addNewItem(null, file.name, null, file);
            },
            isSupported: function () {
                var div = document.createElement('div');
                var supportDraggable = ('draggable' in div) || ('ondragstart' in div && 'ondrop' in div);
                var supportUpload = 'FormData' in window && 'FileReader' in window;

                return supportDraggable && supportUpload;
            },

            /**
             * Sorting methods
             */
            updateFilesOrder: function () {
                // return;
                this.filesOrder = [];

                var draggableItems = document.querySelectorAll('#filegroup-'+ this.settings.group +'-'+ this.settings.locale + ' .draggable-item');

                for (var i = 0; i < draggableItems.length; i++) {
                    var itemId = draggableItems[i].getAttribute('data-item-id');

                    // Newly added items do not have an id yet, so the filename is passed
                    // to we are still able to identify their order upon saving.
                    if (!itemId) {
                        var input = draggableItems[i].querySelector('input[name="files[' + this.settings.group.replace('files-', '') + '][' + this.settings.locale + '][new][]"]');
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
                this.isReordering = !this.isReordering;
            },

            handleSortingStart: function (e) {

                this.sortSource = e.target;
                this.sortSource.style.opacity = '0.4';

                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);// FF support

            },
            handleSortingEnter: function (e) {

                if(!this.sortSource) return;
                this.sortSource.style.opacity = '1';

                // We need our draggable-item, not the child elements
                var target = e.target.classList.contains('draggable-item') ? e.target : this.findAncestor(e.target, '.draggable-item'),
                    draggedItemId = this.sortSource.dataset.itemId,
                    targetItemId = target.dataset.itemId,
                    oldIndex = this.items.findIndex(function(item){ return item.id == draggedItemId}),
                    newIndex = this.items.findIndex(function(item){ return item.id == targetItemId});

                this.handleReorder(oldIndex, newIndex);
            },
            handleReorder(oldIndex, newIndex) {
                this.items.splice(newIndex, 0, this.items.splice(oldIndex, 1)[0]);
            },
            findAncestor: function (el, sel) {
                while ((el = el.parentElement) && !((el.matches || el.matchesSelector).call(el, sel)));
                return el;
            },
            randomString: function(length) {
                let result = '', i = 0;
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

                for (; i < length; i++ ) {
                    result += characters.charAt(Math.floor(Math.random() * characters.length));
                }

                return result;
            }
        }
    }
</script>
