@push('custom-scripts')
<script>

    Vue.component('file', {
        props: ['options', 'group'],
        template: `
                    <div class="thumb">
                        <div class="slim">
                            <img v-if="url" :src="url" :alt="filename">
                            <input v-if="id" type="file" :name="'files['+group+'][replace]['+id+']'" />
                            <input v-else type="file" :name="'files['+group+'][new][]'" />
                        </div>
                        <input v-if="deletion" type="hidden" :name="'files['+group+'][detach][]'" :value="id"/>
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

                return true;
            },
        },
    });

</script>

@endpush
