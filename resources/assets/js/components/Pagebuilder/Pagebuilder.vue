<template>
    <div id="pagebuilder">

        <div v-if="sections.length < 1" class="relative stack border-l-3 border-transparent">
            <a class="btn btn-primary inline-flex items-center" @click="addNewTextSectionAfter(-1)">
                <span class="mr-2"><svg width="18" height="18"><use xlink:href="#zap"/></svg></span>
                <span>Tijd om een eerste stuk tekst toe te voegen</span>
            </a>
        </div>

        <!-- top menu -->
        <div class="pagebuilder-menu-wrapper relative stack border-l-3 border-transparent inset-s">
            <pagebuilder-menu :section="{ sort: -1 }"></pagebuilder-menu>
        </div>

        <draggable :value="sortedSections" 
        v-on:start="minimizeSections"
        v-on:end="changeSectionLocation"
        :options="{
            filter: '.delete-button',
            handle: '.grip-button',
            ghostClass: 'ghost',
            dragClass: 'sortable-drag',
            animation: 150
        }">

            <div v-for="section in sortedSections" v-bind:key="section.key">

                <module-section v-if="section.type === 'module'"
                    sectionKey="modules"
                    v-bind:section="section"
                    v-bind:options="modules"
                    placeholder="Selecteer een module"
                    title="module"
                    class="stack" :class="section.type"></module-section>

                <module-section v-if="section.type === 'page'"
                    sectionKey="modules"
                    v-bind:section="section"
                    v-bind:options="pages"
                    placeholder="Selecteer een pagina"
                    title="pagina"
                    class="stack" :class="section.type"></module-section>

                <module-section v-if="section.type === 'pageset'"
                    sectionKey="pagesets"
                    v-bind:section="section"
                    v-bind:options="pagesets"
                    placeholder="Selecteer een pagina groep"
                    title="pagina groep"
                    class="stack" :class="section.type"></module-section>

                <text-section v-if="section.type === 'text'"
                    v-bind:section="section"
                    v-bind:locales="locales"
                    :single="true"
                    :editor="true"
                    :text-editor="textEditor"
                    title="Pagina text"
                    class="stack" :class="section.type"></text-section>

                <text-section v-if="section.type === 'pagetitle'"
                    v-bind:section="section"
                    v-bind:locales="locales"
                    :single="true"
                    :editor="false"
                    title="Pagina titel"
                    class="stack" :class="section.type"></text-section>

            </div>

        </draggable>
        
        <select name="sections[order][]" multiple style="display:none;">
            <template v-for="section in sortedSections">
                <option v-bind:key="section.key" selected v-if="section.type == 'pagetitle' && !section.id" :value="section.slug"></option>
                <option v-bind:key="section.key" selected v-if="section.type == 'text' && !section.id" :value="section.slug"></option>
                <option v-bind:key="section.key" selected v-else :value="section.id"></option>
            </template>
        </select>

    </div>
</template>

<script>
    import TextSection from './TextSection.vue';
    import ModuleSection from './ModuleSection.vue';
    import PagebuilderMenu from './PagebuilderMenu.vue';
    import draggable from 'vuedraggable';

    export default{
        components: {
            'text-section': TextSection,
            'module-section': ModuleSection,
            'pagebuilder-menu': PagebuilderMenu,
            'draggable': draggable
        },
        props: {
            'defaultSections': { default: function(){ return [] }, type: Array},
            'locales': { default: function(){ return [] }, type: Array},
            'modules' : { default: function(){ return [] }, type: Array},
            'pages' : { default: function(){ return [] }, type: Array},
            'pagesets' : { default: function(){ return [] }, type: Array},
            'textEditor': { default: function() { return "" }, type: String},
        },
        data(){
            return {
                sections: this.defaultSections
            }
        },
        computed: {
            sortedSections() {
                return this.sections.sort(function(a, b) {
                    return a.sort - b.sort;
                });
            },
        },
        created(){
            Eventbus.$on('addingNewTextSectionAfter',(position, component) => {
                this.addNewTextSectionAfter(position);
            });

            Eventbus.$on('addingModuleSectionAfter',(position, component) => {
                this.addModuleSectionAfter(position);
            });

            Eventbus.$on('addingPageSectionAfter',(position, component) => {
                this.addPageSectionAfter(position);
            });

            Eventbus.$on('addingPageSetSectionAfter',(position, component) => {
                this.addPageSetSectionAfter(position);
            });

            Eventbus.$on('addingNewPagetitleSectionAfter',(position, component) => {
                this.addNewPagetitleSectionAfter(position);
            });

            Eventbus.$on('removeThisSection',(position, component) => {
                this.removeSection(position);
            });
        },

        methods: {
            sortSections() {
                this.sections.sort(function(a, b) {
                    return a.sort - b.sort;
                });
            },
            removeSection(index) {
                this.sections.splice(index,1);
                this._resortSectionsAfterDel(index-1);
                this.sortSections();
            },
            changeSectionLocation(event) {

                var isHigherIndex = event.newIndex >= event.oldIndex,
                    newIndex = event.newIndex,
                    oldIndex = event.oldIndex;

                this.sections[oldIndex].sort = newIndex;

                // Calculate indices of elements after oldindex
                for(var i = 0; i < this.sections.length; i++) {
                    if(i > oldIndex) this.sections[i].sort--;
                    else if(i === oldIndex && !isHigherIndex) this.sections[i].sort--;
                }      

                // Calculate indices of elements after newindex
                for(var i = 0; i < this.sections.length; i++) {
                    if(i > newIndex) this.sections[i].sort++;
                    else if(i === newIndex && !isHigherIndex) this.sections[i].sort++;
                }        

                this.maximizeSections();

            },
            minimizeSections() {
                var pagebuilder = document.getElementById('pagebuilder');
                var allSections = this.$el.getElementsByTagName('section');

                for(var i = 0; i < allSections.length; i++) {
                    allSections[i].getElementsByClassName('module-icons-left')[0].classList.add('hide-icons');
                    allSections[i].getElementsByClassName('module-icons-right')[0].classList.add('hide-icons');
                    if(allSections[i].getElementsByClassName('multiselect__single')[0]) {
                        var selectedText = allSections[i].getElementsByClassName('multiselect__single')[0].innerHTML;
                        allSections[i].getElementsByTagName('h3')[0].innerHTML += " - " + selectedText;
                    }
                    allSections[i].getElementsByClassName('to-minimize')[0].style.display = "none";   
                }

                pagebuilder.classList.add('pagebuilder-dragging');
                document.querySelector('body').classList.add('drag-cursor');
            },
            maximizeSections() {
                var pagebuilder = document.getElementById('pagebuilder');
                var allSections = this.$el.getElementsByTagName('section');

                for(var i = 0; i < allSections.length; i++) {
                    allSections[i].getElementsByClassName('module-icons-left')[0].classList.remove('hide-icons');
                    allSections[i].getElementsByClassName('module-icons-right')[0].classList.remove('hide-icons');
                    allSections[i].getElementsByClassName('to-minimize')[0].style.display = "flex";
                    if(allSections[i].getElementsByClassName('multiselect__single')[0]) {
                        var titleText = allSections[i].getElementsByTagName('h3')[0];
                        titleText.innerHTML = titleText.innerHTML.substring(0, titleText.innerHTML.indexOf(' - '));
                    } 
                } 

                pagebuilder.classList.remove('pagebuilder-dragging');
                document.querySelector('body').classList.remove('drag-cursor');
            },
            addNewTextSectionAfter(index){
                this._addNewSectionAfter(index, {
                    type: 'text',
                    slug: this._randomHash(),
                    trans: []
                });
            },
            addModuleSectionAfter(index){
                this._addNewSectionAfter(index, {
                    type: 'module',
                });
            },
            addPageSectionAfter(index){
                this._addNewSectionAfter(index, {
                    type: 'page',
                });
            },
            addPageSetSectionAfter(index){
                this._addNewSectionAfter(index, {
                    type: 'pageset',
                });
            },
            addNewPagetitleSectionAfter(index){
                this._addNewSectionAfter(index, {
                    type: 'pagetitle',
                    slug: this._randomHash(),
                    trans: []
                });
            },
            _addNewSectionAfter(index, data){
                this._resortSectionsAfter(index);
                data.sort = index + 1;
                data.id = data.id || null,
                data.key = data.key || this._randomHash(),
                this.sections.push(data);
            },
            _randomHash(){
                // http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript
                return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            },
            _resortSectionsAfter(index){
                for(let k in this.sections) {

                    if( ! this.sections.hasOwnProperty(k)) continue;

                    if(this.sections[k].sort <= index) continue;
                    this.sections[k].sort++;
                }
            },
            _resortSectionsAfterDel(index){
                for(let k in this.sections) {

                    if( ! this.sections.hasOwnProperty(k)) continue;

                    if(this.sections[k].sort <= index) continue;
                    this.sections[k].sort--;

                }
            }
        }
    }
</script>

<style>

    .pagebuilder-dragging {
        background-color: rgba(255, 255, 255, .3);
        border: 2px dashed rgba(21, 200, 167, 1);
        border-radius: 5px;
        padding: 40px;
    }

    .pagebuilder-dragging .pagebuilder-menu-wrapper {
        display: none;
    }

    .ghost {
        transition: 0.2s all ease;
        background-color: rgba(21, 200, 167, 1);
        height: 10px;
        border-radius: 5px;
        width: 100%;
    }

    .ghost > * {
        display:none;
    }

    .hide-icons {
        opacity: 0 !important;
    }

    .sortable-drag > * {
        opacity: 0.5 !important;
    }

</style>