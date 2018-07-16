<template>
    <section class="stack block inset-s" style="border-left:2px solid lightgreen">
        <input type="hidden" :name="'sections[modules]['+new_or_replace_key+']['+_uid+'][id]'" :value="section.id">
        <span v-html="section.label"></span>
    </section>
</template>
<script>
    export default{
        props: {
            'section': { type: Object },
            'is_new': {default: false, type: Boolean},
        },
        data(){
            return {
                new_or_replace_key: this.is_new ? 'new' : 'replace'
            }
        },
        mounted(){
            //
        },
        methods: {
            //
        }
    }
</script>