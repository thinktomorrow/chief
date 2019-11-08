<template>
	<modal :id="id" class="large-modal" type="modal">
		<div class="gutter-s row overflow-auto" style="height: 400px">
			<div v-if="isLoading && assets.length < 1">
				<svg width="150px"  height="150px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-dual-ring" style="background: none;"><circle cx="50" cy="50" fill="none" stroke-linecap="round" r="40" stroke-width="10" stroke="#5C4456" stroke-dasharray="62.83185307179586 62.83185307179586" transform="rotate(233.955 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform></circle></svg>
			</div>
			<div v-cloak v-for="asset in assets" v-bind:key="asset.id" @click="select(asset)" class="column-4 border rounded border-transparent hover:border-grey-100 hover:bg-grey-50">
				<img :src="asset.url" alt="">
				<div data-equalize-height="metadata-block">
					<strong>{{asset.filename}}</strong>
					<p>{{asset.size}}</p>
				</div>
			</div>
			<input type="hidden" :name="'files[' + group.replace('files-', '') + ']' + '['+ locale +'][new]['+ selected +']'" :value="selected" accept="image/jpeg, image/png, image/bmp, image/svg+xml, image/webp, image/gif" />
		</div>
		<div @click="loadMore()" class="btn btn-primary inline-flex" v-if="assets.length > 0" slot="footer">
			<span class="mr-2">Laad meer</span>
			<span v-if="isLoading">
				<svg width="20px"  height="20px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-dual-ring" style="background: none;"><circle cx="50" cy="50" fill="none" stroke-linecap="round" r="40" stroke-width="10" stroke="#5C4456" stroke-dasharray="62.83185307179586 62.83185307179586" transform="rotate(233.955 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform></circle></svg>
			</span>
		</div>
	</modal>
</template>
<script>
	export default {
		props: {
			group: {required: true, default: ''},
			locale: {required: true, default: ''},
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
			    return 'mediagallery-' + this.group + '-' + this.locale
			}
		},
		created() {
			Eventbus.$on('open-modal',(id) => {
				if(this.id == id && !this.assets.length){
					axios.get('/admin/api/media').then((response) => {
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
				axios.get('/admin/api/media?offset='+this.assets.length).then((response) => {
					this.assets = [...this.assets, ...response.data];
					this.isLoading = false; 
				}).catch((errors) => {
					alert('error');
					this.isLoading = false;
				})
			},
			select: function(asset) {
				var input = document.querySelector('input[name="files[' + this.group.replace('files-', '') + '][' + this.locale + '][new][]"]');

				this.selected = asset.id;
				Eventbus.$emit('close-modal', this.id)
				Eventbus.$emit('mediagallery-loaded-'+ this.group, asset)
			}
		}
	}
</script>
