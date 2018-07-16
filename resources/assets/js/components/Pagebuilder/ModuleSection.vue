<template>
    <section class="stack block inset-s" style="border-left:2px solid lightgreen">

        <span v-html="section.label + ': ' + section.sort"></span>

        <div>
            <chief-multiselect
                    :name="'sections[modules]['+_uid+']'"
                    :options='modules'
                    :multiple="false"
                    grouplabel="group"
                    groupvalues="values"
                    labelkey="label"
                    valuekey="id"
                    placeholder="Selecteer een module."
            >
            </chief-multiselect>
        </div>

    </section>
</template>
<script>
    export default{
        props: {
            'section': { type: Object },
            'modules' : { default: function(){ return [] }, type: Array}
        },
        data(){
            return {

            }
        },
        mounted(){

            Eventbus.$on('updated-select', (name, values) => {

                // Single module allows for one selection
                if(values[0]) {
                    this.section.id = values[0];
                }

                return true;
            });

        },
        methods: {
            //
        }
    }
</script>