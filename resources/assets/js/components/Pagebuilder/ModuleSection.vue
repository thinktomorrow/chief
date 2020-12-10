<template>
    <section @mouseenter="mouseEnter" @mouseleave="mouseLeave" class="shadow border bg-white border-grey-100 block inset relative rounded">

        <h3 class="text-grey-500 mb-0 font-bold" v-if="title" v-text="title"></h3>

        <div class="row to-minimize center-y justify-between">
            <div class="column-6">
                <chief-multiselect
                    :name="'sections['+sectionKey+']['+_uid+']'"
                    :options='options'
                    :multiple="false"
                    :selected="this.selected"
                    grouplabel="group"
                    groupvalues="values"
                    labelkey="label"
                    valuekey="id"
                    :placeholder="placeholder"
                >
                </chief-multiselect>
            </div>
            <a v-if="editModuleUrl" :href="editModuleUrl" target="_blank" class="ml-2 right">bewerk</a>

            <template v-if="showOnlineToggle">
                <span v-if="!isOnline" @click="toggleOnlineStatus" class="btn">Offline. Zet online</span>
                <a v-if="isOnline" @click="toggleOnlineStatus" class="btn">Online. Zet offline</a>
            </template>

        </div>

        <div class="module-icons-left">
            <span class="grip-button inset-xs flex justify-center text-grey-500 text-center my-2 cursor-move">
                <svg width="18" height="18"><use xlink:href="#menu"/></svg>
            </span>
        </div>

        <div class="module-icons-right">
            <span class="delete-button inset-xs flex justify-center text-error text-center my-2 cursor-pointer" @click="removeThisSection(section.sort)">
                <svg width="18" height="18"><use xlink:href="#trash"/></svg>
            </span>
        </div>

    </section>
</template>
<script>

    import toggleOnlineStatusMixin from "./toggleOnlineStatusMixin";
    import MultiSelect from './../MultiSelect.vue';
    import PagebuilderMenu from './PagebuilderMenu.vue';

    export default{
        mixins: [toggleOnlineStatusMixin],
        components: {
            'chief-multiselect': MultiSelect,
            'pagebuilder-menu': PagebuilderMenu
        },
        props: {
            'sectionKey': { required: true, type: String },
            'section': { type: Object },
            'options' : { default: function(){ return [] }, type: Array },
            'placeholder': { default: 'Selecteer een module'},
            'title': {},
            'editUrl': { required: false, type: String },
        },
        data(){
            return {
                show_menu: false,
                selected: this.section.id,
                initialSelection: this.section.id, // used to know when to show the editUrl - which is only valid for the original selection - sorry no autoupdates yet :(
                editModuleUrl: this.editUrl
            }
        },
        created(){

            Eventbus.$on('updated-select', (name, valuesForSelect, values, component) => {

                // Only trigger event coming from own child component
                if(component.$parent._uid != this._uid) return true;

                // Another module, so don't point to the former edit.
                if(this.section.id !== valuesForSelect[0]) {
                    this.editModuleUrl = null;
                    this.showOnlineToggle = false;
                }

                // Selection is back to the original module so we can show the initial options
                if(this.initialSelection === valuesForSelect[0]) {
                    this.editModuleUrl = this.editUrl;
                    this.showOnlineToggle = this.initialShowOnlineToggle;
                }


                // Single module allows for one selection
                if(valuesForSelect[0]) {
                    this.section.id = valuesForSelect[0];
                    this.selected = this.section.id;
                }
                // Deselect a module
                else if( typeof valuesForSelect[0] == "undefined"){
                    this.section.id = null;
                }

                return true;
            });

        },
        methods: {
            removeThisSection(position){
                Eventbus.$emit('removeThisSection', position, this);

                this.active = false;
            },
            mouseEnter(){
                this.$el.getElementsByClassName('module-icons-left')[0].classList.add('reveal-left');
                this.$el.getElementsByClassName('module-icons-right')[0].classList.add('reveal-right');
            },
            mouseLeave(){
                this.$el.getElementsByClassName('module-icons-left')[0].classList.remove('reveal-left');
                this.$el.getElementsByClassName('module-icons-right')[0].classList.remove('reveal-right');
            },
        }
    }
</script>
<style scoped>
.module-icons-left {
    position: absolute;
    top: 0;
    left: -30px;
    bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    opacity: 0;
    width: 40px;
    transition: 0.15s all ease-in;
}
.module-icons-right {
    position: absolute;
    top: 0;
    right: -30px;
    bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    opacity: 0;
    width: 40px;
    transition: 0.15s all ease-in;
}
.reveal-left {
    opacity: 1;
    left: -42px;
    transition: 0.15s all ease-in;
}
.reveal-right {
    opacity: 1;
    right: -42px;
    transition: 0.15s all ease-in;
}
</style>
