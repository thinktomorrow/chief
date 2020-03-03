@push('custom-scripts')
    <script>
        Vue.component('ImageComponent', {
            props: ['item', 'name', 'group'],
            render(){
                return this.$scopedSlots.default({
                    hiddenInputName: this.hiddenInputName,
                    hiddenInputValue: this.hiddenInputValue,
                    name: this.name,
                });
            },
            data: function () {
                return {
                    hiddenInputValue: null,
                    instance: null,
                    // deletion: false,
                    // addedFromGallery: false,
                }
            },
            mounted: function () {
                // this.addedFromGallery = this.options.addedFromGallery || false;

                {{--this.options.didLoad = this.onLoad;--}}
                {{--this.options.didTransform = this.onTransform;--}}
                {{--this.options.labelLoading = '';--}}

                {{--};--}}

                this.instance = new Slim(this.$el.childNodes[0], {

                    // labelLoading: '',

                    // Async upload settings
                    service: '{{ route('chief.api.images.upload') }}',
                    uploadBase64: true,
                    didUpload: this.didUpload,
                    didRemove: this.didRemove,
                    push: true,
                    statusUploadSuccess: '<span class="slim-upload-status-icon"></span>',
                    didReceiveServerError: this.failed,
                    meta: {
                        "managerKey" : "{{ $manager->managerKey() }}",
                        "fieldKey" : this.group,
                    }
                });

                // If a file instance is passed, we want to directly load the file into our cropper
                if (this.item.file) {
                    this.instance.load(this.item.file);
                }

                // Mark element with the same id so serverside we know it needs to be 'replaced' with the same asset.
                if(this.item.existingId) {
                    this.hiddenInputValue = this.item.existingId;
                }
            },
            computed: {
                hiddenInputName: function(){

                    // Only required to indicate which references to watch for
                    this.item.id; this.item.existingId;

                    if(this.item.existingId) return this.name+'['+this.item.existingId+']';

                    if(this.item.id) return this.name+'['+this.item.id+']';

                    // New value should have a random key so it wont conflict with other keys
                    return this.name + '[new_'+ this.randomString(6) +']';
                }
            },
            methods: {
                didUpload: function(error, data, response){
                    this.updateItem({
                        id: response.id,
                        url: response.url,
                        filename: response.filename,
                    });

                    this.hiddenInputValue = response.id;
                },
                failed: function(error, defaultError) {
                    if(error == 'fail') {
                        error = 'Fout bij verwerking. Mogelijk is de afbeelding te groot.';
                    }
                    return "<span class='slim-upload-status-icon'></span>" + error;
                },
                didRemove: function (e, target) {

                    this.hiddenInputValue = null; // null indicated this image for deletion on the server side

                    this.updateItem({
                        deleted: true,
                    });
                },
                onLoad: function () {

                    // Unmark for deletion
                    // this.deletion = false;
console.log('onload...');
                    // Eventbus.$emit('files-loaded-' + this.group, this.id);

                    // Let Slim know it's good to go on - didLoad callback allows for input check prior to Slim.
                    return true;
                },
                updateItem(item){
                    this.$emit('input', {...this.item, ...item});
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
