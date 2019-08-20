<template>
    <section @mouseenter="mouseEnter" @mouseleave="mouseLeave" class="shadow border-l-2 bg-secondary-100 border-primary-500 stack block inset relative rounded-r">
        
        <h3 class="text-grey-500 mb-0 font-bold" v-if="title" v-text="title"></h3>

        <div class="to-minimize mt-2">
            <input type="hidden" :name="'sections[text]['+new_or_replace_key+']['+_uid+'][id]'" :value="section.id">
            <input type="hidden" :name="'sections[text]['+new_or_replace_key+']['+_uid+'][slug]'" :value="section.slug">
            <input type="hidden" :name="'sections[text]['+new_or_replace_key+']['+_uid+'][type]'" :value="section.type">

            <!-- show multiple locales in tab -->
            <tabs v-if="locales.length > 1">
                <div v-if="textEditor == 'redactor' || editor == false">
                    <tab v-for="(locale, key) in locales"
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
                </div>
                <div v-else-if="textEditor == 'quill'" class="w-full">
                    <tab v-for="(locale, key) in locales"
                        :key="key"
                        :id="locale+'-text'"
                        :name="locale"
                        v-cloak>
                        <div 
                            class="inset-s bg-white" 
                            :id="'editor-'+locale+'-'+_uid"
                            v-html="renderInitialContent(locale)">
                        </div>
                        <input 
                            :name="'sections[text]['+new_or_replace_key+']['+_uid+'][trans]['+locale+'][content]'"
                            :type="'hidden'"
                            :value="text_content"
                        >
                    </tab>
                </div>
            </tabs>

            <!-- show single locale not in tabbed format -->
            <template v-if="locales.length == 1">
                <textarea v-if="textEditor == 'redactor' || editor == false"
                        :name="'sections[text]['+new_or_replace_key+']['+_uid+'][trans]['+locales[0]+'][content]'"
                        :id="'editor-'+locales[0]+'-'+_uid"
                        class="inset-s" cols="30" :rows="single ? 1 : 10"
                        v-html="renderInitialContent(locales[0])">
                </textarea>
                <div v-else-if="textEditor == 'quill'" class="w-full">
                    <div 
                        class="inset-s bg-white" 
                        :id="'editor-'+locales[0]+'-'+_uid"
                        v-html="renderInitialContent(locales[0])">
                    </div>
                    <input 
                        :name="'sections[text]['+new_or_replace_key+']['+_uid+'][trans]['+locales[0]+'][content]'"
                        :type="'hidden'"
                        :value="text_content"
                    >
                </div>
            </template>

        </div>

        <pagebuilder-menu :section="section"></pagebuilder-menu>

        <div class="module-icons-left">
            <span class="grip-button inset-xs flex justify-center text-grey-500 text-center my-2 cursor-move">
                <svg width="18" height="18"><use xlink:href="#menu"/></svg>
            </span>
        </div>

        <div class="module-icons-right">
            <span class="delete-button inset-xs flex justify-center text-error text-center my-2 cursor-pointer" @click="removeThisSection(section.sort)">
                <svg width="18" height="18"><use xlink:href="#trash"/></svg>
            </span>
        </div>

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
            'title': {},
            'locales': { default: function() { return [] }, type: Array},
            // Allow redactor editor
            'editor': { default: true, type: Boolean },
            'textEditor': { default: function() { return "" }, type: String },
            // Single line for edit or multiple lines
            'single': { default: false, type: Boolean },
        },
        data(){
            return {
                new_or_replace_key: this.section.id ? 'replace' : 'new',
                show_menu: false,
                text_content: "",
            }
        },
        mounted(){

            if(this.editor) {
                for(var key in this.locales) {
                    if( ! this.locales.hasOwnProperty(key)) continue;
                    if(this.textEditor == 'redactor') {
                        window.$R('#editor-' + this.locales[key] + '-' + this._uid, {
                            // options
                        }); 
                    } else if (this.textEditor == 'quill') {
                        var quill = new Quill('#editor-' + this.locales[0] + '-' + this._uid, {
                            theme: 'snow'
                        });
                        this.text_content = this.renderInitialContent(this.locales[0]);
                        quill.on('text-change', () => {
                            this.text_content = quill.root.innerHTML;
                        });
                    }
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
            removeThisSection(position){
                // text sections worden alleen verwijderd wanneer ze leeg zijn 
                this.removeInput();
                this.active = false;
            },
            mouseEnter(){
                this.$el.getElementsByClassName('module-icons-left')[0].classList.add('reveal-left');
                this.$el.getElementsByClassName('module-icons-right')[0].classList.add('reveal-right');   
            },
            mouseLeave(){
                this.$el.getElementsByClassName('module-icons-left')[0].classList.remove('reveal-left');
                this.$el.getElementsByClassName('module-icons-right')[0].classList.remove('reveal-right');    
            },
            removeInput(){
                let textAreas = this.$el.getElementsByTagName('textarea');
                for(var i = 0; i < textAreas.length; i++) {
                    textAreas[i].innerHTML = "";
                    if(this.section.type === "text") {
                        this.$el.getElementsByClassName('redactor-styles')[i].innerHTML = "";
                    }
                }
                this.$el.style.display = "none";
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