<template>
    <modal :id="id" type="modal" title="Media" size="xl">
        <div v-if="!isLoading || assets.length > 1" class="mb-4 space-y-4">
            <h3 class="h3 display-dark">Kies een bestaande afbeelding</h3>

            <div class="flex items-center justify-end space-x-4">
                <input placeholder="Zoek op bestandsnaam ..." type="text" v-model="searchQuery" />

                <button class="btn btn-primary" @click.prevent="search()">Filter</button>
            </div>
        </div>

        <div data-overflow-scroll class="-mx-3 overflow-scroll row max-h-1/2">
            <div class="flex justify-center w-full" v-if="isLoading && assets.length < 1">
                <svg
                    width="24"
                    height="24"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 100 100"
                    preserveAspectRatio="xMidYMid"
                    class="lds-dual-ring"
                    style="background: none"
                >
                    <circle
                        cx="50"
                        cy="50"
                        fill="none"
                        stroke-linecap="round"
                        r="40"
                        stroke-width="10"
                        stroke="#5C4456"
                        stroke-dasharray="62.83185307179586 62.83185307179586"
                        transform="rotate(233.955 50 50)"
                    >
                        <animateTransform
                            attributeName="transform"
                            type="rotate"
                            calcMode="linear"
                            values="0 50 50;360 50 50"
                            keyTimes="0;1"
                            dur="1s"
                            begin="0s"
                            repeatCount="indefinite"
                        ></animateTransform>
                    </circle>
                </svg>
            </div>

            <div
                v-for="(asset, i) in assets"
                v-bind:key="asset.id"
                @click="select(asset)"
                class="w-full p-1 cursor-pointer xs:w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/5 xl:w-1/6"
            >
                <div
                    :class="{ '!bg-primary-50 border-primary-500': isSelectedAsset(asset) }"
                    class="p-2 border-2 border-white rounded hover:bg-grey-50"
                >
                    <div class="flex items-center justify-center h-32 mb-2 rounded bg-grey-100">
                        <img
                            :id="'media-gallery-image-' + i"
                            :src="asset.url"
                            :alt="asset.filename"
                            class="max-h-full"
                        />
                    </div>
                    <div>
                        <p
                            :title="asset.filename"
                            class="w-full overflow-hidden font-semibold text-grey-900 whitespace-nowrap overflow-ellipsis"
                        >
                            {{ asset.filename }}
                        </p>

                        <div class="text-xs font-medium text-grey-500">
                            <div>{{ asset.dimensions }}</div>
                            <div>{{ asset.size }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div slot="footer" class="flex justify-between w-full" v-if="assets.length > 0">
            <div @click="loadMore()" class="inline-flex btn btn-primary-outline">
                <span>Toon meer afbeeldingen</span>
                <span v-if="isLoading" class="ml-2">
                    <svg
                        width="20px"
                        height="20px"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 100 100"
                        preserveAspectRatio="xMidYMid"
                        class="lds-dual-ring"
                        style="background: none"
                    >
                        <circle
                            cx="50"
                            cy="50"
                            fill="none"
                            stroke-linecap="round"
                            r="40"
                            stroke-width="10"
                            stroke="#ffffff"
                            stroke-dasharray="62.83185307179586 62.83185307179586"
                            transform="rotate(233.955 50 50)"
                        >
                            <animateTransform
                                attributeName="transform"
                                type="rotate"
                                calcMode="linear"
                                values="0 50 50;360 50 50"
                                keyTimes="0;1"
                                dur="1s"
                                begin="0s"
                                repeatCount="indefinite"
                            ></animateTransform>
                        </circle>
                    </svg>
                </span>
            </div>

            <div v-if="multiple" @click="chooseAssets()" class="btn btn-primary">
                <span>Kies geselecteerde assets</span>
            </div>
        </div>
    </modal>
</template>

<script>
export default {
    props: {
        reference: { required: true },
        locale: { required: true, default: '' },
        limit: { required: false, default: 12 },
        replace: { required: false, default: '' },
        uploaded: { required: true, default: [] },
        url: { required: true, default: '/admin/api/media' },
        multiple: { required: false, default: false },
    },
    data() {
        return {
            searchQuery: '',
            isLoading: true,
            assets: [],
            selected: [],
        };
    },
    computed: {
        id: function () {
            return 'mediagallery-' + this.reference + '-' + this.locale;
        },
    },
    created() {
        Eventbus.$on('open-modal', (id) => {
            if (this.id == id && !this.assets.length) {
                axios
                    .get(`${this.url}?limit=${this.limit}&excluded=${this.uploaded}&search=${this.searchQuery}`)
                    .then((response) => {
                        this.assets = response.data;
                        this.isLoading = false;
                    })
                    .catch((errors) => {
                        alert('error');
                    });
            }
        });
    },
    methods: {
        loadMore: function () {
            this.isLoading = true;
            axios
                .get(
                    `${this.url}?offset=${this.assets.length}&limit=${this.limit}&excluded=${this.uploaded}&search=${this.searchQuery}`
                )
                .then((response) => {
                    this.assets = [...this.assets, ...response.data];
                    this.isLoading = false;
                    let image = document.getElementById(`media-gallery-image-${this.assets.length - this.limit - 1}`);
                    setTimeout(() => {
                        this.$el
                            .querySelector('[data-overflow-scroll]')
                            .scrollTo({ top: image.offsetTop, behavior: 'smooth' });
                    }, 50);
                })
                .catch((errors) => {
                    alert('error');
                    this.isLoading = false;
                });
        },
        select: function (asset) {
            if (this.multiple) {
                if (this.isSelectedAsset(asset)) {
                    this.selected = this.selected.filter((item) => item.id != asset.id);
                } else {
                    this.selected.push(asset);
                }
            } else {
                this.selected.push(asset);
                this.chooseAssets();
            }
        },
        chooseAssets: function () {
            let localAssets = this.assets;
            this.selected.forEach(function (asset) {
                localAssets.splice((item) => item.id == asset.id);
            });
            this.assets = localAssets;

            Eventbus.$emit('close-modal', this.id);
            Eventbus.$emit('mediagallery-loaded-' + this.reference, this.selected);
            this.selected = [];
        },
        isSelectedAsset: function (asset) {
            return this.selected.find((item) => item.id == asset.id);
        },
        search: function () {
            this.isLoading = true;
            axios
                .get(`${this.url}?limit=${this.limit}&excluded=${this.uploaded}&search=${this.searchQuery}`)
                .then((response) => {
                    this.assets = response.data;
                    this.isLoading = false;
                })
                .catch((errors) => {
                    alert('error');
                    this.isLoading = false;
                });
        },
    },
};
</script>
