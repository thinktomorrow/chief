@push('custom-styles')
    <link rel="stylesheet" href="{{ asset('/assets/back/css/vendor/slim.min.css') }}">
@endpush
@push('custom-scripts')
    <script src="{{ asset('/assets/back/js/vendor/slim.min.js') }}"></script>
    <script>
        Vue.component('slim', {
            props: ['options', 'name', 'group'],
            template: `
                    <div class="thumb">
                        <div class="slim">
                            <img v-if="url" :src="url" :alt="filename">
                            <input ref="hiddenInput" style="margin-bottom:0;" type="hidden" :name="hiddenInputValue" />

                            <input style="margin-bottom:0;" type="file" :name="name+'[]'" accept="image/*" />
                        </div>
                    </div>
                `,
            data: function () {
                return {
                    existingId: this.options.id || null, // This is the existing asset id if any
                    id: this.options.id || null, // This is the id of the newest linked asset.
                    url: this.options.url || null, // Already existing link
                    file: this.options.file || null, // Newly created file object
                    filename: this.options.filename || null,
                    instance: null,
                    deletion: false,
                    addedFromGallery: false,
                }
            },
            mounted: function () {
                this.options.didRemove = this.markForDeletion;
                this.options.didLoad = this.onLoad;
                this.options.didTransform = this.onTransform;
                this.addedFromGallery = this.options.addedFromGallery || false;
                this.options.service = '/admin/api/assets/upload';
                this.options.uploadBase64 = true;

                this.instance = new Slim(this.$el.childNodes[0], this.options);

                // If a file instance is passed, we want to directly load the file into our cropper
                if (this.file) {
                    this.instance.load(this.file, () => {
                        this.upload();
                    });
                }

                // Mark element with the same id so serverside we know it needs to be 'replaced' with the same asset.
                if(this.existingId) {
                    this.$refs.hiddenInput.value = this.existingId;
                }

            },
            computed: {
                hiddenInputValue: function(){

                    // Only required to indicate which references to watch for
                    this.existingId; this.id;

                    if(this.existingId) return this.name+'['+this.existingId+']';

                    if(this.id) return this.name+'['+this.id+']';

                    return this.name + '[]';
                }
            },
            methods: {
                upload: function(){
                    this.instance.upload((error, data, response) => {

                        if(error) {
                            return console.error(error);
                        }

                        this.addedFromGallery = true;
                        this.id = response.id;
                        this.url = response.url;
                        this.filename = response.filename;

                        // Mark element for replacement with the newly uploaded asset
                        this.$refs.hiddenInput.value = this.id;
                    });
                },

                markForDeletion: function (e, target) {
                    this.deletion = true;
                    var self = this;

                    // Mark element for deletion
                    this.$refs.hiddenInput.value = null;

                    Eventbus.$emit('file-deletion-' + this.group, {id: self.id, newImage: target});
                },
                onTransform: function(){
                    this.upload();
                },
                onLoad: function () {

                    // Unmark for deletion
                    this.deletion = false;

                    Eventbus.$emit('files-loaded-' + this.group, this.id);

                    // Let Slim know it's good to go on - didLoad callback allows for input check prior to Slim.
                    return true;
                },
            },
        });

    </script>

@endpush
