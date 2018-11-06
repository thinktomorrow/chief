<template>
    <section @mouseenter="mouseEnter" @mouseleave="mouseLeave" class="section-item stack block inset relative">
        <h3 class="pagebuilder-section-title" v-if="title" v-text="title"></h3>

        <div class="to-minimize">
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

        </div>

        <pagebuilder-menu :section="section"></pagebuilder-menu>

        <div class="module-icons-left">
            <span class="grip-button icon icon-menu inset-xs"></span>
        </div>

        <div class="module-icons-right">
            <span class="delete-button icon icon-trash inset-xs" @click="removeThisSection(section.sort)"></span>
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
            'locales': { default: function(){ return [] }, type: Array},

            // Allow redactor editor
            'editor': { default: true, type: Boolean },

            // Single line for edit or multiple lines
            'single': { default: false, type: Boolean },

            'title': {}
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
                    });
                }
            }

            var dragElement = document.createElement('img');
            dragElement.src = '/chief-assets/back/img/favicon.png';

            this.$el.addEventListener('dragstart', function(event) {
                event.dataTransfer.setDragImage(dragElement, 0, 0);
            });

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
                // Eventbus.$emit('removeThisSection', position, this);

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
                        this.$el.getElementsByClassName('redactor-styles')[0].innerHTML = "";
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