@push('custom-scripts')
    <script>

        Vue.component('imagesupload', {
            props: ['preselected', 'group', 'locale'],
            data: function () {
                return {
                    isDraggingOver: false,
                    isDropped: false,

                    items: [],

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

                var self = this;
                const preselection = this.preselected ? JSON.parse(this.preselected) : [];

                preselection.forEach((preselectedItem) => {
                    self.addNewItem(preselectedItem.id, preselectedItem.filename, preselectedItem.url);
                });

                setTimeout(this.updateFilesOrder, 100);

                Eventbus.$on('mediagallery-loaded-' + this.group, function (asset){
                    self.addNewItem(asset.id, asset.filename, asset.url);
                    setTimeout(this.updateFilesOrder, 100);
                })

            },
            computed: {
                hasValidUpload: function(){
                    var result = this.items.map(function(item){
                        return item.deleted;
                    });
                    return result.includes(undefined) || result.includes(false);
                },
            },
            mounted: function () {
                this.updateFilesOrder();
            },
            methods: {
                addNewItem: function(id, filename, url, file){
                    this.items.push({
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

                    var draggableItems = document.querySelectorAll('#filegroup-'+ this.group +'-'+ this.locale + ' .draggable-item');

                    for (var i = 0; i < draggableItems.length; i++) {
                        var itemId = draggableItems[i].getAttribute('data-item-id');

                        // Newly added items do not have an id yet, so the filename is passed
                        // to we are still able to identify their order upon saving.
                        if (!itemId) {
                            var input = draggableItems[i].querySelector('input[name="files[' + this.group.replace('files-', '') + '][' + this.locale + '][new][]"]');
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

                    if(!this.sortSource) return;
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
                }
            },
        });
    </script>
@endpush
