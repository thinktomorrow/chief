@push('custom-styles')
    {{-- <link rel="stylesheet" href="{{ chief_cached_asset('/chief-assets/back/css/vendors/slim.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('/assets/back/css/vendor/slim.min.css') }}">
@endpush
@push('custom-scripts')
    {{-- <script src="{{ chief_cached_asset('/chief-assets/back/js/vendors/slim.kickstart.min.js') }}"></script> --}}
    <script src="{{ asset('/assets/back/js/vendor/slim.js') }}"></script>
    <script>
        Vue.component('slim', {
            props: ['options', 'name', 'group'],
            template: `
                    <div class="thumb">
                        <div class="slim">
                            <img v-if="url" :src="url" :alt="filename">
                            <input v-if="id && !newUpload" type="file" :name="name+'[replace]['+id+']'" accept="image/jpeg, image/png, image/bmp, image/svg+xml, image/webp, image/gif"/>
                            <input v-if="newUpload" type="hidden" :name="name+'[new]['+ id +']'" :value="id" accept="image/jpeg, image/png, image/bmp, image/svg+xml, image/webp, image/gif" />
                            <input v-else type="file" :name="name+'[new][]'" accept="image/jpeg, image/png, image/bmp, image/svg+xml, image/webp, image/gif" />
                        </div>
                        <input v-if="deletion" type="hidden" :name="name+'[delete][]'" :value="id"/>
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
                    newUpload: false
                }
            },
            mounted: function () {
                this.options.didRemove = this.markForDeletion;
                this.options.didLoad = this.onLoad;
                this.newUpload = this.options.newUpload;
                this.instance = new Slim(this.$el.childNodes[0], this.options);


                // If a file instance is passed, we want to directly load the file into our cropper
                if (this.file) {
                    this.instance.load(this.file);
                }
            },
            methods: {
                markForDeletion: function (e) {
                    // If event contains a reference to an image,
                    // this is a replace action and not a delete one
                    this.deletion = true;
                    
                    Eventbus.$emit('file-deletion-' + this.group, this.id);
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

    </script>

@endpush
