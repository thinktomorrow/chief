<template>
    <section class="stack block inset-s" style="border-left:2px solid lightgreen">

        <!--<input type="hidden" :name="'sections[modules][new]['+_uid+'][id]'" :value="section.id">-->
        <span v-html="section.label + ': ' + section.sort"></span>

        <div>
            <chief-multiselect
                    :name="'sections[modules][new]['+_uid+']'"
                    :options='modules'
                    :multiple="false"
                    grouplabel="group"
                    groupvalues="values"
                    labelkey="label"
                    valuekey="id"
                    placeholder="..."
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
            console.log('duddu');

            Eventbus.$on('updated-select', (name, values) => {

                // Currently only one entry selection
                if(values[0]) {
                    console.log(values[0]);
                    //this.section.id = values[0];
                }

                return true;
            });

        },
        methods: {
            //
        }
    }
</script>