@push('custom-styles')
    {{-- <link rel="stylesheet" href="{{ chief_cached_asset('/chief-assets/back/css/vendors/slim.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('/assets/back/css/vendor/slim.min.css') }}">
@endpush
@push('custom-scripts')
    {{-- <script src="{{ chief_cached_asset('/chief-assets/back/js/vendors/slim.kickstart.min.js') }}"></script> --}}
    <script src="{{ asset('/assets/back/js/vendor/slim.js') }}"></script>
    <script>
        Vue.component('slim', {
            props: ['options', 'group'],
            template: `
                    <div class="thumb">
                        <div class="slim">
                            <img v-if="url" :src="url" :alt="filename">
                            <input v-if="id" type="file" :name="group+'[replace]['+id+']'" accept="image/jpeg, image/png, image/bmp, image/svg+xml, image/webp, image/gif"/>
                            <input v-else type="file" :name="group+'[new][]'" accept="image/jpeg, image/png, image/bmp, image/svg+xml, image/webp, image/gif" />
                        </div>
                        <input v-if="deletion" type="hidden" :name="group+'[delete][]'" :value="id"/>
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

    </script>

@endpush
