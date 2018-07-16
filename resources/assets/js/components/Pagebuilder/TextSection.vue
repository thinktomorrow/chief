<template>
    <section class="stack block inset-s" style="border-left:2px solid lightgreen">
        <input type="hidden" :name="'sections[text]['+new_or_replace_key+']['+_uid+'][id]'" :value="section.id">
        <input type="hidden" :name="'sections[text]['+new_or_replace_key+']['+_uid+'][slug]'" :value="section.slug">
        <tabs>
            <tab
                    v-for="(locale, key) in locales"
                    :key="key"
                    :id="locale+'-translatable-fields'"
                    :name="locale"
                    v-cloak>
            <textarea
                    :name="'sections[text]['+new_or_replace_key+']['+_uid+'][trans]['+locale+'][content]'"
                    :id="'editor-'+locale+'-'+_uid"
                    class="inset-s" cols="30" rows="10"
                    v-html="renderInitialContent(locale)"></textarea>
            </tab>
        </tabs>
    </section>
</template>
<script>

    export default{
        props: {
            'section': { type: Object },
            'is_new': {default: false, type: Boolean},
            'locales': { default: function(){ return {} }, type: Object}
        },
        data(){
            return {
                new_or_replace_key: this.is_new ? 'new' : 'replace'
            }
        },
        mounted(){

            for(var key in this.locales) {
                if( ! this.locales.hasOwnProperty(key)) continue;

                window.$R('#editor-' + this.locales[key] + '-' + this._uid);
            }

        },
        methods: {
            renderInitialContent(locale){

                let translation = this.section['trans'][locale];
                if(!translation) return '';

                let content = this.section['trans'][locale].content;
                if(!content) return '';

                return content;
            },

            addSection(){

            },
            removeSection(){

            }
        }
    }
</script>