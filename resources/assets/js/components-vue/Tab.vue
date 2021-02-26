<template>
	<div v-show="isActive" role="tabpanel">
		<slot></slot>
	</div>
</template>
<script>
	export default {
		props: {
			name: {required: false, default: ''},
			active: {default: false, type: Boolean},
			id: { default:'' },
			options: {default: function(){ return {}; }, type: Object }
		},

		data() {
			return {
				isActive: this.active,
			};
		},

		computed: {
		    hash(){
		        return this.id ? '#' + this.id : '#' + this.hashedName
			},
			hashedName() {
				return this.name.toLowerCase().replace(/ /g, '-');
			},
		},
	}
</script>
