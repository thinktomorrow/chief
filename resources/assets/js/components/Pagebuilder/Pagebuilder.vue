<template>
    <div>

        <span @click="addNewTextSectionAfter(-1)">+ nieuwe text toevoegen</span>
        <span @click="addModuleSectionAfter(-1)">+ nieuwe module toevoegen</span>

        <template v-for="section in sortedSections">

            <text-section v-if="section.collection == 'text'"
                  v-bind:key="section.key"
                v-bind:section="section"
                v-bind:locales="locales"
                class="stack"></text-section>

            <module-section v-if="section.collection != 'text'"
                    v-bind:key="section.key"
                    v-bind:section="section"
                    v-bind:modules="modules"
                  class="stack"></module-section>

            <span @click="addNewTextSectionAfter(section.sort)">+ nieuwe text toevoegen</span>
            <span @click="addModuleSectionAfter(section.sort)">+ nieuwe module toevoegen</span>



        </template>

        <select name="sections[order][]" multiple style="display:none;">
            <template v-for="section in sortedSections">
                <option selected v-if="section.collection == 'text' && !section.id" :value="section.slug"></option>
                <option selected v-else :value="section.id"></option>
            </template>
        </select>

        <!--<select type="hidden" name="sections[modules][new]"></select>-->
        <!--<select type="hidden" name="sections[modules][replace]"></select>-->
        <!--<select type="hidden" name="sections[modules][remove]"></select>-->

    </div>
</template>
<script>
    import TextSection from './TextSection.vue';
    import ModuleSection from './ModuleSection.vue';
    // For modules we show module name
    // For pages we try to combine them into one section
    // Text modules are shown the content

    //    section.id (if null it is considered a new one)
    //    section.slug (required for new ones)

    export default{
        components: {
            'text-section': TextSection,
            'module-section': ModuleSection,
        },
        props: {
            'defaultSections': { default: function(){ return [] }, type: Array},
            'locales': { default: function(){ return {} }, type: Object},
            'modules' : { default: function(){ return [] }, type: Array},
        },
        data(){
            return {
                sections: this.defaultSections
//                sections: [
//                    {
//                        sort: 2,
//                        collection: 'text',
//                        id: 1,
//                        slug: 'dudu',
//                        trans: {
//                            nl: {
//                                content: 'this is de content yall',
//                            },
//                            fr: {
//                                content: 'ceci c\'est une pipe',
//                            }
//                        }
//                    },
//                    {
//                        sort: 3,
//                        collection: 'module',
//                        id: 'foobar@2',
//                        label: 'Foobar 2',
//                        group: 'product'
//                    },
//                    {
//                        sort: 1,
//                        collection: 'module',
//                        id: 'foobar@1',
//                        label: 'Foobar 1',
//                        group: 'product'
//                    },
//                    {
//                        sort:5,
//                        collection: 'text',
//                        id: 2,
//                        slug: 'dudu',
//                        trans: {
//                            nl: {
//                                content: 'this is de <strong>content</strong> yall',
//                            },
//                        }
//                    }
//                ],
            }
        },
        computed: {
            sortedSections() {
                return this.sections.sort((a, b) => a.sort > b.sort );
            }
        },
        methods: {
            addNewTextSectionAfter(section_sort){

                let index = section_sort + 1;
                this._resortSectionsAfter(section_sort);

                this.sections.push({
                    key: this._randomHash(),
                    sort: index,
                    collection: 'text',
                    id: null,
                    slug: this._randomHash(),
                    trans: []
                });
            },
            addModuleSectionAfter(section_sort){

                let index = section_sort + 1;
                this._resortSectionsAfter(section_sort);

                this.sections.push({
                    key: this._randomHash(),
                    sort: index,
                    collection: 'module',
                    id: 'dkjfkldqjfdkmj@1',
                    label: 'Module',
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