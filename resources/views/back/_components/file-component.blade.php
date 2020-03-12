@push('custom-styles')

@endpush
@push('custom-scripts')
    <script>
        Vue.component('file', {
            props: ['options', 'name', 'group', 'locale'],
            template: `
                    <div class="border border-grey-100 rounded inset-s center-y bg-white">
                        <input ref="hiddenInput" type="hidden" :name="hiddenInputKey" :value="hiddenInputValue"/>
                        <div v-if="deletion" class="w-full text-error">Bestand wordt verwijderd.</div>
                        <div v-else class="w-full">
                            <div><strong>@{{ filename }}</strong></div>
                            <span class="text-grey-300">
                                @{{ mimetype }} | @{{ size }}
                            </span>

                            <div v-if="url" class="pr-2 ml-auto">
                                <a :href="url" target="_blank">Bekijk document</a>
                            </div>

                            <div class="text-error" v-if="error" v-html="error"></div>
                        </div>
                        <div v-if="!deletion">
                            <svg class="cursor-pointer" @click="markForDeletion" width="18" height="18"><use xlink:href="#x"/></svg>
                        </div>
                    </div>
                `,
            data: function () {
                return {
                    service: '{{ route('chief.api.files.upload') }}',

                    existingId: this.options.id || null, // This is the existing asset id if any
                    id: this.options.id || null, // This is the id of the newest linked asset.

                    url: this.options.url || null, // Already existing link
                    filename: this.options.filename || null,
                    mimetype: this.options.mimetype || null,
                    size: this.options.size || null,

                    file: this.options.file || null, // Newly created file object
                    deletion: false,
                    addedFromGallery: false,
                    error: null,
                }
            },
            mounted: function () {
                this.addedFromGallery = this.options.addedFromGallery || false;

                // Newly added file
                if(this.file){
                    this.upload();
                    this.filename = this.file.name;
                    this.mimetype = this.file.type;
                    this.size = this.file.size;
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
                },
                hiddenInputValue: function(){

                    // Put on first line so vue knows which elements to watch
                    this.id; this.deletion;

                    if(this.deletion) return null;

                    // Mark element with the same id so serverside we know it needs to be 'replaced' with the same asset.
                    if(this.existingId && !this.id) {
                        return this.existingId;
                    }

                    return this.id;
                }
            },
            methods: {
                upload: function(){

                    // Put file in FormData in order to get transferred to the server
                    let formData = new FormData();
                    formData.append('file', this.file);
                    formData.append('locale', this.locale);
                    formData.append('managerKey', "{{ $manager->managerKey() }}");
                    formData.append('fieldKey', this.group);

                    window.axios.post(this.service, formData, {headers: {
                        'Content-Type': 'multipart/form-data'
                    }}).then((response) => {

                        // PostsizeTooLarge is returned as 200 instead of 419 to meet the redactor requirements
                        if(typeof response.data == 'string' && response.data.includes('POST Content-Length')) {
                            this.showError('Ongeldig bestand. Dit bestand is te groot om op te laden.');
                            return false;
                        }

                        const responseData = response.data;

                        this.id = responseData.id;
                        this.url = responseData.url;
                        this.filename = responseData.filename;
                        this.mimetype = responseData.mimetype;
                        this.size = responseData.size;

                    }).catch((error) => {
                        this.id = null;
                        console.error(error);

                        // Possible server errors with uploads are also a: 413 Request Entity Too Large
                        this.showError(error.response.data.message || 'Ongeldig bestand. Mogelijk is dit te groot.');
                    });
                },
                showError: function(error){
                    this.error = error;
                },
                markForDeletion: function (e) {
                    this.deletion = true;

                    Eventbus.$emit('file-deletion-' + this.group, {id: this.id});
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
