@push('custom-styles')
    <link rel="stylesheet" href="{{ asset('/assets/back/css/vendor/slim.min.css') }}">
    <style type="text/css">

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

        .thumb [data-state=empty] {
            height: 80px;
        }
    </style>
@endpush
@push('custom-scripts')
    <script src="{{ asset('/assets/back/js/vendor/slim.min.js') }}"></script>
    <script>
        Vue.component('slim', {
            props: ['options', 'name', 'group'],
            template: `
                    <div class="thumb">
                        <div class="slim">
                            <img v-show="url" :src="url" :alt="filename">
                            <input ref="hiddenInput" style="margin-bottom:0;" type="hidden" :name="hiddenInputKey" />

                            <input style="margin-bottom:0;" type="file" :name="name+'[]'" accept="image/jpeg, image/png, image/svg+xml, image/webp" />
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
                this.addedFromGallery = this.options.addedFromGallery || false;

                this.options.didRemove = this.markForDeletion;
                this.options.didLoad = this.onLoad;
                this.options.didTransform = this.onTransform;
                this.options.labelLoading = '';
                this.options.service = '{{ route('chief.api.images.upload') }}';
                this.options.uploadBase64 = true;
                this.options.push = true;
                this.options.statusUploadSuccess = '<span class="slim-upload-status-icon"></span>';
                this.options.didReceiveServerError = this.failed;
                this.options.meta = {
                    "managerKey" : "{{ $manager->managerKey() }}",
                    "fieldKey" : this.group,
                };

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
                hiddenInputKey: function(){

                    // Only required to indicate which references to watch for
                    this.existingId; this.id;

                    if(this.existingId) return this.name+'['+this.existingId+']';

                    if(this.id) return this.name+'['+this.id+']';

                    // New value should have a random key so it wont conflict with other keys
                    return this.name + '[new_'+ this.randomString(6) +']';
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
                failed: function(error, defaultError) {
                    if(error == 'fail') {
                        error = 'Fout bij verwerking. Mogelijk is de afbeelding te groot.';
                    }
                    return "<span class='slim-upload-status-icon'></span>" + error;
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
                randomString: function(length) {
                    let result = '',
                        i = 0;

                    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

                    for (; i < length; i++ ) {
                        result += characters.charAt(Math.floor(Math.random() * characters.length));
                    }

                    return result;
                }
            },
        });

    </script>

@endpush
