<template>
    <div v-if="localErrors.has(field)">
        <slot name="icon">
            <i class="icon icon-alert-circle"></i>
        </slot>
        <span v-text="localErrors.get(field)"></span>
    </div>
</template>

<script>
export default {
    props: {
        field: { required: true },
        errors: { required: false},
    },
    data(){
        return {
	        localErrors: this.errors ? ( this.errors instanceof Errors ? this.errors :  (new Errors()).record(this.errors)) : this.$parent.errors
        }
    },
    created() {
	    Eventbus.$on('clearErrors', (field) => {
	    	this.clear(field);
	    });
    },
    methods: {
    	clear(field) {
            this.localErrors.clear(field);
        }
    },
	watch: {
		errors: function () {
			this.localErrors = this.errors ? ( this.errors instanceof Errors ? this.errors :  (new Errors()).record(this.errors)) : this.$parent.errors
		}
	},
};
</script>
