<template>
    <div id="pagebuilder">

        <div v-if="sections.length < 1" class="relative stack" style="border-left:3px solid transparent;">
            <span class="btn btn-primary squished" @click="addNewTextSectionAfter(-1)">
                <span class="icon icon-zap icon-fw"></span>
                <span>Tijd om een eerste stuk tekst toe te voegen</span>
            </span>
        </div>

        <!-- top menu -->
        <div class="relative stack inset-s" style="border-left:3px solid transparent;">
            <pagebuilder-menu :section="{ sort: -1 }"></pagebuilder-menu>
        </div>

        <draggable class="relative stack" 
        :value="sortedSections" 
        @end="changeSectionLocation" 
        @start="minimizeSections"
        :options="{
            handle: '.grip-button',
            ghostClass: 'ghost',
        }">

            <template v-for="section in sortedSections">

                <text-section v-if="section.type == 'text'"
                    v-bind:key="section.key"
                    v-bind:section="section"
                    v-bind:locales="locales"
                    :single="true"
                    :editor="true"
                    title="Pagina text"
                    class="stack item" :class="section.type"></text-section>
            
                <text-section v-if="section.type == 'pagetitle'"
                    v-bind:key="section.key"
                    v-bind:section="section"
                    v-bind:locales="locales"
                    :single="true"
                    :editor="false"
                    title="Pagina titel"
                    class="stack item" :class="section.type"></text-section>

                <module-section v-if="section.type == 'module'"
                    v-bind:key="section.key"
                    sectionKey="modules"
                    v-bind:section="section"
                    v-bind:options="modules"
                    placeholder="Selecteer een module"
                    title="module"
                    class="stack item" :class="section.type"></module-section>

                <module-section v-if="section.type == 'page'"
                    v-bind:key="section.key"
                    sectionKey="modules"
                    v-bind:section="section"
                    v-bind:options="pages"
                    placeholder="Selecteer een pagina"
                    title="pagina"
                    class="stack item" :class="section.type"></module-section>

                <module-section v-if="section.type == 'pageset'"
                    v-bind:key="section.key"
                    sectionKey="pagesets"
                    v-bind:section="section"
                    v-bind:options="pagesets"
                    placeholder="Selecteer een pagina groep"
                    title="pagina groep"
                    class="stack item" :class="section.type"></module-section>

            </template>

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
            }
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
            },
            changeSectionLocation(event) {
                var temp = this.sections[event.oldIndex];
                this.removeSection(event.oldIndex);
                this._addNewSectionAfter(event.newIndex-1, temp);
                this.maximizeSections();
            },
            minimizeSections() {
                document.getElementById('pagebuilder').classList.add('stretch');
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
            },
            maximizeSections() {
                document.getElementById('pagebuilder').classList.remove('stretch');
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
            },
            addNewTextSectionAfter(section_sort){
                this._addNewSectionAfter(section_sort, {
                    type: 'text',
                    slug: this._randomHash(),
                    trans: []
                });
            },
            addModuleSectionAfter(section_sort){
                this._addNewSectionAfter(section_sort, {
                    type: 'module',
                });
            },
            addPageSectionAfter(section_sort){
                this._addNewSectionAfter(section_sort, {
                    type: 'page',
                });
            },
            addPageSetSectionAfter(section_sort){
                this._addNewSectionAfter(section_sort, {
                    type: 'pageset',
                });
            },
            addNewPagetitleSectionAfter(section_sort){
                this._addNewSectionAfter(section_sort, {
                    type: 'pagetitle',
                    slug: this._randomHash(),
                    trans: []
                });
            },
            _addNewSectionAfter(section_sort, data){

                let index = section_sort + 1;
                this._resortSectionsAfter(section_sort);

                data.sort = index;
                data.id = data.id || null,
                data.key = data.key || this._randomHash(),

                this.sections.push(data);
                this.sortSections();
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
.section-item {
    border-left:3px solid rgba(21, 200, 167, 1);
    background-color:rgba(21, 200, 167, 0.05);
}

.ghost {
    transition: 0.2s all ease;
    background-color: rgba(255,255,255,0.5);
    border-left: transparent;
}

.ghost > * {
    display:none;
}

.hide-icons {
    opacity: 0 !important;
}

.stretch {
    width: 130%;
    margin-left: -15%;
    transition: 0.2s all ease;
}

@media (max-width:1024px) {
    .stretch {
        width: 110%;
        margin-left: -5%;
    }
}

@media (max-width:768px) {
    .stretch {
        width: 100%;
        margin-left: 0;
    }
}


</style>