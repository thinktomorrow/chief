<template>
    <section class="stack block inset relative" style="border-left:3px solid rgba(21, 200, 167, 1); background-color:rgba(21, 200, 167, 0.05)">
        <div class="row">
            <div class="column-6">
                <chief-multiselect
                        :name="'sections[modules]['+_uid+']'"
                        :options='modules'
                        :multiple="false"
                        :selected="section.id"
                        grouplabel="group"
                        groupvalues="values"
                        labelkey="label"
                        valuekey="id"
                        placeholder="Selecteer een module."
                >
                </chief-multiselect>
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
            'section': { type: Object },
            'modules' : { default: function(){ return [] }, type: Array}
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

                return true;
            });

        },
        methods: {
            //
        }
    }
</script>