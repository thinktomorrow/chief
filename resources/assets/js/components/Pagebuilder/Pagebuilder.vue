<template>
    <div>

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

        <template v-for="section in sortedSections">

            <text-section v-if="section.type == 'text'"
                v-bind:key="section.key"
                v-bind:section="section"
                v-bind:locales="locales"
                class="stack" :class="section.type"></text-section>

            <text-section v-if="section.type == 'pagetitle'"
                  v-bind:key="section.key"
                  v-bind:section="section"
                  v-bind:locales="locales"
                  :single="true"
                  :editor="false"
                  title="Pagina titel"
                  class="stack" :class="section.type"></text-section>

            <module-section v-if="section.type == 'module'"
                v-bind:key="section.key"
                sectionKey="modules"
                v-bind:section="section"
                v-bind:options="modules"
                placeholder="Selecteer een module"
                title="module"
                class="stack" :class="section.type"></module-section>

            <module-section v-if="section.type == 'page'"
                v-bind:key="section.key"
                sectionKey="modules"
                v-bind:section="section"
                v-bind:options="pages"
                placeholder="Selecteer een pagina"
                title="pagina"
                class="stack" :class="section.type"></module-section>

            <module-section v-if="section.type == 'pageset'"
                v-bind:key="section.key"
                sectionKey="pagesets"
                v-bind:section="section"
                v-bind:options="pagesets"
                placeholder="Selecteer een pagina groep"
                title="pagina groep"
                class="stack" :class="section.type"></module-section>

        </template>

        <select name="sections[order][]" multiple style="display:none;">
            <template v-for="section in sortedSections">
                <option selected v-if="section.type == 'pagetitle' && !section.id" :value="section.slug"></option>
                <option selected v-if="section.type == 'text' && !section.id" :value="section.slug"></option>
                <option selected v-else :value="section.id"></option>
            </template>
        </select>

    </div>
</template>
<script>
    import TextSection from './TextSection.vue';
    import ModuleSection from './ModuleSection.vue';
    import PagebuilderMenu from './PagebuilderMenu.vue';

    export default{
        components: {
            'text-section': TextSection,
            'module-section': ModuleSection,
            'pagebuilder-menu': PagebuilderMenu
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
                return this.sections.sort((a, b) => a.sort > b.sort );
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
        },
        methods: {
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
            },
            removeSection(){
                //
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
            }
        }
    }
</script>