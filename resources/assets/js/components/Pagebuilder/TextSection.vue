<template>
    <section class="stack block inset relative" style="border-left:3px solid #14c8a7">
        <input type="hidden" :name="'sections[text]['+new_or_replace_key+']['+_uid+'][id]'" :value="section.id">
        <input type="hidden" :name="'sections[text]['+new_or_replace_key+']['+_uid+'][slug]'" :value="section.slug">
        <input type="hidden" :name="'sections[text]['+new_or_replace_key+']['+_uid+'][type]'" :value="section.type">

        <!-- show multiple locales in tab -->
        <tabs v-if="locales.length > 1">
            <tab
                v-for="(locale, key) in locales"
                :key="key"
                :id="locale+'-text'"
                :name="locale"
                v-cloak>
                <textarea
                    :name="'sections[text]['+new_or_replace_key+']['+_uid+'][trans]['+locale+'][content]'"
                    :id="'editor-'+locale+'-'+_uid"
                    class="inset-s" cols="30" :rows="single ? 1 : 10"
                    v-html="renderInitialContent(locale)">
                </textarea>
            </tab>
        </tabs>

        <!-- show single locale not in tabbed format -->
        <template v-if="locales.length == 1">
            <textarea
                    :name="'sections[text]['+new_or_replace_key+']['+_uid+'][trans]['+locales[0]+'][content]'"
                    :id="'editor-'+locales[0]+'-'+_uid"
                    class="inset-s" cols="30" :rows="single ? 1 : 10"
                    v-html="renderInitialContent(locales[0])">
            </textarea>
        </template>


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
            'locales': { default: function(){ return [] }, type: Array},

            // Allow redactor editor
            'editor': { default: true, type: Boolean },

            // Single line for edit or multiple lines
            'single': { default: false, type: Boolean },
        },
        data(){
            return {
                new_or_replace_key: this.section.id ? 'replace' : 'new',
                show_menu: false,
            }
        },
        mounted(){

            if(this.editor) {
                for(var key in this.locales) {
                    if( ! this.locales.hasOwnProperty(key)) continue;

                    window.$R('#editor-' + this.locales[key] + '-' + this._uid, {
                        // options
                        buttons: ['html', 'format', 'bold', 'italic', 'lists', 'image', 'file', 'link', 'video', 'snippets']
                    });
                }
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
        }
    }
</script>