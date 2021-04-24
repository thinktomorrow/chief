<template>
    <modal :id="id" class="large-modal" type="modal">
        <div v-if="!isLoading || assets.length > 1" class="row items-center mb-4">
            <div class="column-6">
                <h2 class="text-2xl">Kies een bestaande afbeelding</h2>
            </div>

            <div class="column-6">
                <div class="formgroup">
                    <div class="input-group flex justify-end items-center">
                        <input
                            placeholder="Zoek op bestandsnaam ..."
                            class="input inset-s"
                            type="text"
                            v-model="searchQuery"
                        />
                        <button class="btn btn-primary-outline ml-4" @click.prevent="search()">Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <div data-overflow-scroll class="row overflow-scroll max-h-3/4 -mx-3">
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
                class="xs-column-12 s-column-6 m-column-4 l-column-3 xl-column-2 p-1 cursor-pointer"
            >
                <div
                    :class="{ 'bg-grey-50 border-secondary-500': isSelectedAsset(asset) }"
                    class="p-2 border-2 rounded border-transparent hover:bg-grey-50"
                >
                    <div class="bg-grey-100 flex items-center justify-center h-32 mb-2 rounded">
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
                            class="font-bold"
                            style="text-overflow: ellipsis; overflow: hidden; width: 100%; white-space: nowrap"
                        >
                            {{ asset.filename }}
                        </p>
                        <div class="flex justify-between">
                            <p class="block text-grey-400 mb-2">{{ asset.dimensions }}</p>
                            <p class="block text-grey-400 mb-2">{{ asset.size }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div slot="footer" class="flex justify-between w-full" v-if="assets.length > 0">
            <div @click="loadMore()" class="btn btn-primary-outline inline-flex">
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
                </span>
            </div>
            <div v-if="multiple" @click="chooseAssets()" class="btn btn-primary inline-flex">
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
