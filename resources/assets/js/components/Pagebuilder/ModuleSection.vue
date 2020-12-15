<template>
    <section @mouseenter="mouseEnter" @mouseleave="mouseLeave"
             class="relative block bg-white border rounded shadow border-grey-100 inset"
             :class="!isOnline ? 'bg-grey-100' : 'bg-white'">

        <div class="justify-between row center-y">
            <h3 class="mb-0 font-bold text-grey-500" v-if="title" v-text="title"></h3>

            <template v-if="showOnlineToggle">
                <div class="flex items-center text-sm leading-none">
                    <span class="mr-1" :class="isOnline ? 'text-grey-300' : 'text-grey-600'">Offline</span>
                    <span class="mr-1 text-grey-300">|</span>
                    <span class="mr-2" :class="isOnline ? 'text-grey-600' : 'text-grey-300'">Online</span>

                    <div @click="toggleOnlineStatus" class="flex items-center w-10 h-5 rounded-full cursor-pointer border-grey-100" :class="isOnline ? 'bg-primary-500' : 'bg-grey-200'">
                        <div class="w-3 h-3 m-1 bg-white rounded-full transform transition duration-300 ease-in-out" :class="isOnline ? 'translate-x-5' : ''"></div>
                    </div>
                </div>
            </template>
        </div>

        <div class="items-end justify-between mt-2 row to-minimize">
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

            <div class="column-6">
                <a v-if="editModuleUrl" :href="editModuleUrl" target="_blank" class="ml-2 right">Bewerk</a>
            </div>
        </div>

        <div class="module-icons-left">
            <span class="flex justify-center my-2 text-center cursor-move grip-button inset-xs text-grey-500">
                <svg width="18" height="18"><use xlink:href="#menu"/></svg>
            </span>
        </div>

        <div class="module-icons-right">
            <span class="flex justify-center my-2 text-center cursor-pointer delete-button inset-xs text-error" @click="removeThisSection(section.sort)">
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
