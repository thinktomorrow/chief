<template>
    <div v-if="localErrors.has(field)">
        <slot name="icon">
            <span><svg width="18" height="18" class="float-left mr-2"><use xlink:href="#alert-circle"/></svg></span>
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
