<template>
    <section class="stack block inset relative" style="border-left:3px solid #14c8a7">
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

        <pagebuilder-menu :section="section"></pagebuilder-menu>
    </section>
</template>
<script>

    import PagebuilderMenu from './PagebuilderMenu.vue';

    export default{
        components: {
            'pagebuilder-menu': PagebuilderMenu
        },
        props: {
            'section': { type: Object },
            'locales': { default: function(){ return {} }, type: Object}
        },
        data(){
            return {
                new_or_replace_key: this.section.id ? 'replace' : 'new',
                show_menu: false,
            }
        },
        mounted(){

            for(var key in this.locales) {
                if( ! this.locales.hasOwnProperty(key)) continue;

                window.$R('#editor-' + this.locales[key] + '-' + this._uid, {
                    clickToEdit: true,
                });
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