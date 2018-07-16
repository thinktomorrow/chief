<template>
    <div>

        <span @click="addNewTextSection(0)">+ nieuwe text toevoegen</span>
        <span @click="addModuleSection(0)">+ nieuwe module toevoegen</span>

        <template v-for="(section,key) in sections">

            <text-section v-if="section.type == 'text'"
                v-bind:section="section"
                v-bind:locales="locales"
                class="stack"></text-section>

            <module-section v-if="section.type == 'module'"
                  v-bind:section="section"
                  class="stack"></module-section>

            <span @click="addNewTextSection(key+1)">+ nieuwe text toevoegen</span>
            <span @click="addModuleSection(key+1)">+ nieuwe module toevoegen</span>

        </template>

        <!--<select type="hidden" name="sections[text][remove]"></select>-->
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
            'locales': { default: function(){ return {} }, type: Object}
        },
        data(){
            return {
                sections: [
                    {
                        type: 'module',
                        id: 'foobar@1',
                        label: 'Foobar 1',
                        group: 'product'
                    },
                    {
                        type: 'text',
                        id: 1,
                        slug: 'dudu',
                        trans: {
                            nl: {
                                content: 'this is de content yall',
                            },
                            fr: {
                                content: 'ceci c\'est une pipe',
                            }
                        }
                    },
                    {
                        type: 'module',
                        id: 'foobar@2',
                        label: 'Foobar 2',
                        group: 'product'
                    },
                    {
                        type: 'text',
                        id: 2,
                        slug: 'dudu',
                        trans: {
                            nl: {
                                content: 'this is de <strong>content</strong> yall',
                            },
                        }
                    }
                ],
            }
        },
        methods: {
            addNewTextSection(index){
                console.log(this.sections);
                console.log(index);
                this.sections.splice(index, 0, {
                    type: 'text',
                    is_new: true,
                    id: null,
                    slug: this._randomHash(),
                    trans: {
                        nl: {
                            content: 'this is de content yalldfqsdf qsdfdqsfqsdfdsqfqdf',
                        },
                        fr: {
                            content: 'ceci c\'est une pipedf qsdf qsdfk dqsjflksqjdflmkqsjdfmlkjsd',
                        },
                        en: {
                            content: 'ceci c\'est une pipedf qsdf qsdfk dqsjflksqjdflmkqsjdfmlkjsd',
                        }
                    }
                });
                console.log(this.sections);

            },
            addModuleSection(index){
                console.log(this.sections);
                console.log(index);
                this.sections.splice(index, 0, {
                    type: 'module',
                    is_new: true,
                    id: 'dkjfkldqjfdkmj@1',
                    label: 'Foobar',
                    group: 'product'
                });

                console.log(this.sections);

            },
            removeSection(){

            },
            _randomHash(){

                // http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript
                return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            },
        }
    }
</script>