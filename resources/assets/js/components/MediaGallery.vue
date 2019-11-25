<template>
	<modal :id="id" class="large-modal" type="modal">

		<div v-if="!isLoading || assets.length > 1" class="row mb-4">
			<h2>Kies een afbeelding ...</h2>
		</div>

		<div data-overflow-scroll class="row overflow-scroll justify-center max-h-3/4">

			<div v-if="isLoading && assets.length < 1">
				<svg width="32px" height="32px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-dual-ring" style="background: none;"><circle cx="50" cy="50" fill="none" stroke-linecap="round" r="40" stroke-width="10" stroke="#5C4456" stroke-dasharray="62.83185307179586 62.83185307179586" transform="rotate(233.955 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform></circle></svg>
			</div>

			<div v-for="(asset, i) in assets" v-bind:key="asset.id" @click="select(asset)" class="column-3 border rounded border-transparent hover:bg-grey-50 p-2 cursor-pointer">
				<div class="bg-grey-100 flex items-center justify-center h-32 mb-4 rounded">
					<img :id="'media-gallery-image-'+i" :src="asset.url" :alt="asset.filename" class="max-h-full">
				</div>
				<div>
					<p class="font-bold">{{ asset.filename }}</p>
					<div class="flex justify-between">
						<p class="block text-grey-400 mb-2">{{ asset.dimensions }}</p>
						<p class="block text-grey-400 mb-2">{{ asset.size }}</p>
					</div>
				</div>
			</div>

			<input v-if="selected" type="hidden" :name="'files[' + group.replace('files-', '') + ']' + '['+ locale +'][new]['+ selected +']'" :value="selected" accept="image/jpeg, image/png, image/bmp, image/svg+xml, image/webp, image/gif" />
		</div>

		<div @click="loadMore()" class="flex justify-center w-full" v-if="assets.length > 0" slot="footer">
			<div class="btn btn-primary inline-flex">
				<span>Toon meer afbeeldingen</span>
				<span v-if="isLoading" class="ml-2">
					<svg width="20px"  height="20px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-dual-ring" style="background: none;"><circle cx="50" cy="50" fill="none" stroke-linecap="round" r="40" stroke-width="10" stroke="#5C4456" stroke-dasharray="62.83185307179586 62.83185307179586" transform="rotate(233.955 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform></circle></svg>
				</span>
			</div>
		</div>

	</modal>
</template>
<script>
	export default {
		props: {
			group: {required: true, default: ''},
			locale: {required: true, default: ''},
			limit: {required: false, default: 12},
			replace: {required: false, default: ''}
		},
		data(){
            return {
				isLoading: true,
				assets: [],
				selected: null
            }
        },
	    computed: {
		    id: function() {
				var id = 'mediagallery-' + this.group + '-' + this.locale;
				if(this.replace) id += '-'+ this.replace;

			    return id;
			}
		},
		created() {
			Eventbus.$on('open-modal',(id) => {
				if(this.id == id && !this.assets.length){
					axios.get(`/admin/api/media?limit=${this.limit}`).then((response) => {
						this.assets = response.data;
						this.isLoading = false;
					}).catch((errors) => {
						alert('error');
					})
				}
        	});
		},
		methods: { 
			loadMore: function() {
				this.isLoading = true;
				axios.get(`/admin/api/media?offset=${this.assets.length}&limit=${this.limit}`).then((response) => {
					this.assets = [...this.assets, ...response.data];
					this.isLoading = false;
					let image = document.getElementById(`media-gallery-image-${this.assets.length-this.limit-1}`);
					setTimeout(() => {
						this.$el.querySelector('[data-overflow-scroll]').scrollTo({ top: image.offsetTop, behavior: 'smooth' });
					}, 50);
				}).catch((errors) => {
					alert('error');
					this.isLoading = false;
				})
			},
			select: function(asset) {
				var input = document.querySelector('input[name="files[' + this.group.replace('files-', '') + '][' + this.locale + '][new][]"]');

				this.selected = asset.id;
				Eventbus.$emit('close-modal', this.id)
				Eventbus.$emit('mediagallery-loaded-'+ this.group, asset, this.replace)
			}
		}
	}
</script>
