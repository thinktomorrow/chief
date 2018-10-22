<template>
    <section @mouseenter="mouseEnter" @mouseleave="mouseLeave" class="stack block inset relative" style="border-left:3px solid rgba(21, 200, 167, 1); background-color:rgba(21, 200, 167, 0.05)">
        <h3 class="pagebuilder-section-title" v-if="title" v-text="title"></h3>
        <div class="row to-minimize">
            <div class="column-6">
                <chief-multiselect
                        :name="'sections['+sectionKey+']['+_uid+']'"
                        :options='options'
                        :multiple="false"
                        :selected="section.id"
                        grouplabel="group"
                        groupvalues="values"
                        labelkey="label"
                        valuekey="id"
                        :placeholder="placeholder"
                >
                </chief-multiselect>
            </div>
        </div>

        <pagebuilder-menu :section="section"></pagebuilder-menu>

        <div class="module-icons-left">
            <span class="grip-button icon icon-menu inset-xs"></span>
        </div>

        <div class="module-icons-right">
            <span class="delete-button icon icon-trash inset-xs" @click="removeThisSection(section.sort)"></span>
        </div>

    </section>
</template>
<script>

    import MultiSelect from './../MultiSelect.vue';
    import PagebuilderMenu from './PagebuilderMenu.vue';

    export default{
        components: {
            'chief-multiselect': MultiSelect,
            'pagebuilder-menu': PagebuilderMenu
        },
        props: {
            'sectionKey': { required: true, type: String },
            'section': { type: Object },
            'options' : { default: function(){ return [] }, type: Array },
            'placeholder': { default: 'Selecteer een module'},
            'title': {}
        },
        data(){
            return {
                show_menu: false,
            }
        },
        mounted(){

            Eventbus.$on('updated-select', (name, valuesForSelect, values, component) => {

                // Only trigger event coming from own child component
                if(component.$parent._uid != this._uid) return true;

                // Single module allows for one selection
                if(valuesForSelect[0]) {
                    this.section.id = valuesForSelect[0];
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
            }
        }
    }
</script>
<style scoped>
.delete-button {
    color:red;
    /* border-left: 2px solid red; */
    margin: 0.5rem 0;
    text-align: center;
}
.grip-button {
    color: rgb(30,30,30);
    /* border-right: 2px solid rgb(30,30,30); */
    margin: 0.5rem 0;
    text-align: center;
}
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
section {
    position: relative;
}
.reveal-left {
    opacity: 1;
    left: -43px;
    transition: 0.15s all ease-in;
}
.reveal-right {
    opacity: 1;
    right: -40px;
    transition: 0.15s all ease-in;
}
</style>