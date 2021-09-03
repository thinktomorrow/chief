@push('custom-styles')

@endpush
@push('custom-scripts')
    <script>
        Vue.component('file', {
            props: ['options', 'name', 'group', 'locale', 'uploadUrl'],
            template: `
                    <div class="flex items-center p-4 bg-white border rounded-lg border-grey-100">
                        <input ref="hiddenInput" type="hidden" :name="hiddenInputKey" :value="hiddenInputValue"/>

                        <div v-if="isImage" class="flex-shrink-0 w-1/6">
                             <img loading="lazy" :src="thumbUrl" :alt="filename" class="rounded" />
                        </div>

                        <div v-if="deletion" class="w-full mx-4">
                            <p class="text-grey-700">Dit bestand wordt verwijderd bij het bewaren van jouw aanpassingen.</p>
                        </div>

                        <div v-else class="w-full mx-4 space-y-1">
                            <div><span class="font-semibold text-grey-900">@{{ filename }}</span></div>

                            <span class="text-grey-400">
                                @{{ mimetype }} | @{{ size }}
                            </span>

                            <div v-if="url" class="pr-2 ml-auto">
                                <a v-if="isImage" :href="url" target="_blank" class="link link-primary">Bekijk afbeelding</a>
                                <a v-else :href="url" target="_blank" class="link link-primary">Bekijk document</a>
                            </div>

                            <div v-if="showLoader" class="pr-2 ml-auto">
                                <p class="text-grey-700">Bezig met opladen...</p>
                            </div>

                            <div class="text-red-500" v-if="error" v-html="error"></div>
                        </div>

                        <div v-if="!deletion" class="mr-4">
                            <span @click="markForDeletion">
                                <x-chief-icon-label type="delete" class="text-red-500"></x-chief-icon-label>
                            </span>
                        </div>
                    </div>
                `,
            data: function () {
                return {
                    existingId: this.options.id || null, // This is the existing asset id if any
                    id: this.options.id || null, // This is the id of the newest linked asset.

                    url: this.options.url || null, // Already existing link
                    thumbUrl: this.options.thumbUrl || this.options.url || null,
                    filename: this.options.filename || null,
                    isImage: this.options.isImage || false,
                    mimetype: this.options.mimetype || null,
                    size: this.options.size || null,

                    file: this.options.file || null, // Newly created file object
                    deletion: false,
                    addedFromGallery: false,
                    error: null,
                    showLoader: false
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

                    this.showLoader = true;
                    Eventbus.$emit('disable-update-form');

                    // Put file in FormData in order to get transferred to the server
                    let formData = new FormData();
                    formData.append('file', this.file);
                    formData.append('locale', this.locale);

                    window.axios.post(this.uploadUrl, formData, {headers: {
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
                        this.thumbUrl = responseData.url;
                        this.filename = responseData.filename;
                        this.mimetype = responseData.mimetype;
                        this.size = responseData.size;
                        this.isImage = responseData.isImage || this.isImage;

                    }).catch((error) => {
                        this.id = null;
                        console.error(error);

                        // Possible server errors with uploads are also a: 413 Request Entity Too Large
                        this.showError((error.response ? error.response.data.message : false) || 'Ongeldig bestand. Mogelijk is dit te groot.');
                    }).then(() => {
                        // The second 'then' is always executed
                        this.showLoader = false;
                        Eventbus.$emit('enable-update-form');
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
