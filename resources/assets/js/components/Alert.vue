<template>
    <div v-show="isVisible" class="alert --raised" :class="typeclass">
        <span class="icon-warning pull-left"></span>
        <span class="alert__copy column-9" v-html="body"></span>
        <span class="alert__btn" @click="isVisible = false"><i class="icon-x"></i></span>
    </div>
</template>
<script>
    export default {
	    props: {
		    'type': {default: 'success'},
		    'body': {required: true },
	    },
	    data() {
		    return {
			    isVisible: true,
		    }
	    },
	    computed: {
		    typeclass: function() {
			    return 'alert--' + this.type
		    }
	    }
    }
</script>
