<template>
    <section class="stack block inset" style="border-left:3px solid #14c8a7">
        <h3>PAGINAS</h3>
        <chief-multiselect
                :name="'sections[modules]['+_uid+']'"
                :options='modules'
                :multiple="true"
                :selected="section.pageCollection"
                grouplabel="group"
                groupvalues="values"
                labelkey="label"
                valuekey="id"
                placeholder="Selecteer een of meerdere pagina's."
        >
        </chief-multiselect>
    </section>
</template>
<script>

    import MultiSelect from './../MultiSelect.vue';

    export default{
        components: {
            'chief-multiselect': MultiSelect
        },
        props: {
            'section': { type: Object },
            'modules' : { default: function(){ return [] }, type: Array}
        },
        data(){
            return {

            }
        },
        mounted(){

            Eventbus.$on('updated-select', (name, valuesForSelect, values, component) => {

                // Only trigger event coming from own child component
                if(component.$parent._uid != this._uid) return true;

                this.section.pageCollection = [];

                for(let k in valuesForSelect) {
                    if( ! valuesForSelect.hasOwnProperty(k)) continue;
                    this.section.pageCollection.push(valuesForSelect[k]);
                }

                return true;
            });

        },
        methods: {
            //
        }
    }
</script>