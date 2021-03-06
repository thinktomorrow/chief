<script>
export default {
    props: ['reference', 'item', 'name', 'group', 'uploadUrl'],
    render() {
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
        };
    },
    mounted: function () {
        this.instance = new Slim(this.$el.childNodes[0], {
            label: 'Klik om een afbeelding te selecteren',
            labelLoading: 'De afbeelding is aan het laden ...',

            // Async upload settings
            service: this.uploadUrl, //'/admin/api/assets/images/upload',
            uploadBase64: true,
            didUpload: this.didUpload,
            didLoad: this.didLoad,
            didRemove: this.didRemove,
            push: true,
            statusUploadSuccess: '<span class="slim-upload-status-icon"></span> Afbeelding geüpload',
            didReceiveServerError: this.failed,
            meta: {
                fieldKey: this.group,
            },
        });

        // If a file instance is passed, we want to directly load the file into our cropper
        if (this.item.file) {
            this.instance.load(this.item.file);
        }

        // Mark element with the same id so serverside we know it needs to be 'replaced' with the same asset.
        if (this.item.existingId) {
            this.hiddenInputValue = this.item.existingId;
        }
    },
    computed: {
        hiddenInputName: function () {
            // Only required to indicate which references to watch for
            this.item.id;
            this.item.existingId;

            if (this.item.existingId) return this.name + '[' + this.item.existingId + ']';

            if (this.item.id) return this.name + '[' + this.item.id + ']';

            // New value should have a random key so it wont conflict with other keys
            return this.name + '[new_' + this.randomString(6) + ']';
        },
    },
    methods: {
        didUpload: function (error, data, response) {
            Eventbus.$emit('image-upload-response' + this.reference, this.instance);

            if (error) {
                Eventbus.$emit('create-notification', 'error', error);
                console.error(error);
                return;
            } else {
                Eventbus.$emit(
                    'create-notification',
                    'success',
                    'De afbeelding is correct geüpload en klaar om te gebruiken!'
                );
            }

            this.updateItem({
                id: response.id,
                url: response.url,
                filename: response.filename,
            });

            this.hiddenInputValue = response.id;
        },
        failed: function (error, defaultError) {
            Eventbus.$emit('image-upload-response' + this.reference, this.instance);

            if (error == 'fail') {
                error = 'Fout bij verwerking. Mogelijk is de afbeelding te groot.';
            }

            return `<span class="slim-upload-status-icon"></span> ${error}`;
        },
        didRemove: function (e, target) {
            this.hiddenInputValue = null; // null indicated to the server that this image should be deleted

            this.updateItem({
                deleted: true,
            });
        },
        didLoad: function () {
            if (!this.item.id || this.item.deleted) {
                Eventbus.$emit('image-upload-request' + this.reference, this.instance);
            }

            // When swapping an existing image with another by clicking on the slim dropzone to upload a replacement image,
            // slim first emits a didRemove action. This is followed by the didLoad action so that's why we ensure
            // here that any image that is being loaded does not have the deleted flag.
            if (this.item.deleted) {
                this.updateItem({
                    deleted: false,
                });
            }

            // Let Slim know it's good to go on - didLoad callback allows for input check prior to Slim.
            return true;
        },
        updateItem(item) {
            // Unknown bug: when updating the entire item as a whole to the parent component, the item object itself is not
            // being updated in this component. Looks like because we are doing a full swap (items[index] = newItem)
            // because updating a single property (items[index].filename = newItem.filename) seems to propagate like expected.
            for (const prop in item) {
                const value = item[prop];
                this.$emit('input', prop, value);
            }
        },
        randomString: function (length) {
            let result = '',
                i = 0;
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            for (; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * characters.length));
            }

            return result;
        },
    },
};
</script>
