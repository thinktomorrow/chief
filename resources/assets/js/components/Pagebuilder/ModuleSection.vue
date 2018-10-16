<template>
    <section class="stack block inset relative" style="border-left:3px solid rgba(21, 200, 167, 1); background-color:rgba(21, 200, 167, 0.05)">
        <h3 class="pagebuilder-section-title" v-if="title" v-text="title"></h3>
        <div class="row">
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
            <div class="column-6">
                <span class="btn btn-primary squished-s delete-button" 
                @click="removeThisSection(section.sort)">
                    <span class="icon icon-trash"></span>
                </span>
            </div>
        </div>

        <pagebuilder-menu :section="section"></pagebuilder-menu>

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
        }
    }
</script>
<style scoped>
.delete-button {
    background-color: red;
    float: right;
    border: red;
    z-index: 100;
}
</style>