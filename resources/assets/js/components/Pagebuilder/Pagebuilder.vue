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
                class="stack"></text-section>

            <module-section v-if="section.type == 'module'"
                v-bind:key="section.key"
                v-bind:section="section"
                v-bind:modules="modules"
                class="stack"></module-section>

            <!--<pages-section v-if="section.type == 'pages'"-->
                    <!--v-bind:key="section.key"-->
                    <!--v-bind:section="section"-->
                    <!--v-bind:modules="modules"-->
                  <!--class="stack"></pages-section>-->

        </template>

        <select name="sections[order][]" multiple style="display:none;">
            <template v-for="section in sortedSections">
                <option selected v-if="section.type == 'text' && !section.id" :value="section.slug"></option>
                <option selected v-else :value="section.id"></option>
            </template>
        </select>

    </div>
</template>
<script>
    import TextSection from './TextSection.vue';
    import ModuleSection from './ModuleSection.vue';
    import PageModuleSection from './PageModuleSection.vue';
    import PagebuilderMenu from './PagebuilderMenu.vue';
    // For modules we show module name
    // For pages we try to combine them into one section
    // Text modules are shown the content

    //    section.id (if null it is considered a new one)
    //    section.slug (required for new ones)

    export default{
        components: {
            'text-section': TextSection,
            'module-section': ModuleSection,
            'pages-section' : PageModuleSection,
            'pagebuilder-menu': PagebuilderMenu
        },
        props: {
            'defaultSections': { default: function(){ return [] }, type: Array},
            'locales': { default: function(){ return [] }, type: Array},
            'modules' : { default: function(){ return [] }, type: Array},
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
        },
        methods: {
            addNewTextSectionAfter(section_sort){

                let index = section_sort + 1;
                this._resortSectionsAfter(section_sort);

                this.sections.push({
                    id: null,
                    key: this._randomHash(),
                    sort: index,
                    type: 'text',
                    slug: this._randomHash(),
                    trans: []
                });
            },
            addModuleSectionAfter(section_sort){

                let index = section_sort + 1;
                this._resortSectionsAfter(section_sort);

                this.sections.push({
                    id: null,
                    key: this._randomHash(),
                    sort: index,
                    type: 'module',
                });
            },
            removeSection(){

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