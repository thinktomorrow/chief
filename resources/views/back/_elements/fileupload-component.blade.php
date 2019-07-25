@push('custom-scripts')
    <script>

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
                /** */
                if(this.items.length < 1) {
                    // this.fileDropArea  = document.querySelector('#file-drop-area-' + this.group);
                    // this.fileInput     = this.fileDropArea.querySelector('input');
                    // this.fileInputName = this.fileInput.name;
                }

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
