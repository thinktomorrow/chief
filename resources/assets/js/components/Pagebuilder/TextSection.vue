<template>
    <section @mouseenter="mouseEnter" @mouseleave="mouseLeave"
             class="shadow border border-grey-100 block inset relative rounded"
             :class="!isOnline ? 'bg-grey-100' : 'bg-white'">

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
                            :data-locale="locale"
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
                            :data-lang="locale"
                            v-html="renderInitialContent(locale)">
                        </div>
                        <input
                            :name="'sections[text]['+new_or_replace_key+']['+_uid+'][trans]['+locale+'][content]'"
                            :type="'hidden'"
                            :value="text_content[key].value"
                        >
                    </tab>
                </div>
            </tabs>

            <!-- show single locale not in tabbed format -->
            <template v-if="locales.length == 1">
                <textarea v-if="textEditor == 'redactor' || editor == false"
                    :data-locale="locales[0]"
                    :name="'sections[text]['+new_or_replace_key+']['+_uid+'][trans]['+locales[0]+'][content]'"
                    :id="'editor-'+locales[0]+'-'+_uid"
                    class="inset-s" cols="30" :rows="single ? 1 : 10"
                    v-html="renderInitialContent(locales[0])">
                </textarea>
                <div v-else-if="textEditor == 'quill'" class="w-full">
                    <div
                        class="inset-s bg-white"
                        :data-lang="locales[0]"
                        :id="'editor-'+locales[0]+'-'+_uid"
                        v-html="renderInitialContent(locales[0])">
                    </div>
                    <input
                        :name="'sections[text]['+new_or_replace_key+']['+_uid+'][trans]['+locales[0]+'][content]'"
                        :type="'hidden'"
                        :value="text_content[0].value"
                    >
                </div>
            </template>

            <template v-if="showOnlineToggle">
                <span v-if="!isOnline" @click="toggleOnlineStatus" class="btn absolute btn mt-1 right-0 top-0">Offline <span class="underline">Zet online</span></span>
                <a v-if="isOnline" @click="toggleOnlineStatus" class="btn absolute btn mt-1 right-0 top-0">Online <span class="underline">Zet offline</span></a>
            </template>

        </div>

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

    import toggleOnlineStatusMixin from "./toggleOnlineStatusMixin";
    import PagebuilderMenu from './PagebuilderMenu.vue';

    export default{
        mixins: [toggleOnlineStatusMixin],
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
                text_content: this.locales.map(locale => {
                    return {
                        locale,
                        value: this.section['trans'][locale] ? this.section['trans'][locale].content : '',
                    }
                }),
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
                        this.text_content[key].value = this.renderInitialContent(this.locales[key]);
                        const quill = new Quill('#editor-' + this.locales[key] + '-' + this._uid, {
                            theme: 'snow'
                        });
                        quill.on('text-change', () => {
                            for(let i = 0; i < this.text_content.length; i++) {
                                if(this.text_content[i].locale == quill.container.dataset.lang) {
                                    this.text_content[i].value = quill.root.innerHTML;
                                }
                            }
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
                if(this.textEditor == 'redactor')
                {
                    let textAreas = this.$el.getElementsByTagName('textarea');

                    for(var i = 0; i < textAreas.length; i++) {
                        textAreas[i].innerHTML = "";
                        if(this.section.type === "text") {
                            this.$el.getElementsByClassName('redactor-styles')[i].innerHTML = "";
                        }
                    }
                }else if(this.textEditor == 'quill')
                {
                    let quilleditors = this.$el.getElementsByClassName('ql-editor');
                    for(var i = 0; i < quilleditors.length; i++) {
                        quilleditors[i].innerHTML = "";
                        if(this.section.type === "text") {
                            quilleditors.innerHTML = "";
                        }
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
