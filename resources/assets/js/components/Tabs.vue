<template>
    <div>
        <slot name="tabnav" :tabs="tabs">
            <ul v-if="!hideNav" role="tablist" class="flex w-full border-b border-grey-200 inline-group-s">
                <li v-for="tab in tabs" role="presentation">
                    <slot name="tabname" :tab="tab">
                        <a v-html="tab.name"
                           @click="selectTab(tab)"
                           :href="tab.hash"
                           :aria-controls="tab.hash"
                           :aria-selected="tab.isActive"
                           role="tab"
                           class="block squished-s --bottomline"
                           :class="{'active': tab.isActive }"
                        ></a>
                    </slot>
                </li>
            </ul>
        </slot>
        <div class="clearfix">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    export default {

        props: {
            'external_nav': {default:false, type: Boolean},
        },

	    data() {
		    return {
		        tabs: [],
                hideNav: this.external_nav,
		    };
	    },

	    created() {
		    this.tabs = this.$children;

            Eventbus.$on('select-tab',(hash) => {
                this.selectTab(hash);
            });
	    },

	    mounted(){
            window.addEventListener('hashchange',() => this.selectTab(window.location.hash), false);

            // Trigger the event if there is a hash on page load
            if(window.location.hash && typeof this.findTab(window.location.hash) != "undefined"){
                window.dispatchEvent(new Event('hashchange'));
            } else if(this.tabs.length > 0 && !this.isAnyTabActive()){
                this.selectTab(this.tabs[0]);
            }
        },

	    methods: {
		    selectTab(selectedTab) {

                // Hash can be passed as well, so let's find the tab by hash first
                if(typeof selectedTab == "string") selectedTab = this.findTab(selectedTab);

                // Halt here if targeted tab does not reside in this component or it's already active
                if(typeof selectedTab == "undefined" || selectedTab.isActive) return;

			    this.tabs.forEach(tab => {
				    tab.isActive = (tab == selectedTab);
			    });
		    },

            findTab(hash){
                return this.tabs.find(tab => tab.hash == hash);
            },

            isAnyTabActive(){
                return (typeof this.tabs.find(tab => {
                    return tab.isActive;
                }) != "undefined");
            }
	    }
    }
</script>
