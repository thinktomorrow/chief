<template>
    <div class="absolute flex justify-center items-center w-full h-8 how-on-hover center-y" style="z-index:1;bottom:-24px;left:-14px">
        <span v-show="!active" @click="active = true" class="block menu-trigger bg-secondary-50 rounded-full cursor-pointer mx-auto hover:text-secondary-600">
            <svg width="24" height="24" class="fill-current"><use xlink:href="#plus"/></svg>
        </span>

        <span v-show="active" @click="active = false" class="block menu-trigger bg-secondary-50 rounded-full cursor-pointer mx-auto hover:text-secondary-600">
            <svg width="24" height="24" class="fill-current"><use xlink:href="#min"/></svg>
        </span>

        <div v-show="active" class="ml-6 pagebuilder-menu-items float-right absolute bg-white inset --raised rounded flex inline-group-l" style="transform: translateX(50%);">
            
            <div class="flex flex-col">

                <p class="stack-xs">Creëer een ...</p>

                <div class="inline-group-s">
                    <div class="float-left cursor-pointer">
                        <span title="pagina titel toevoegen" @click="addingNewPagetitleSectionAfter(section.sort)" class="btn btn-o-secondary squished-xs center-y">
                            <svg width="18" height="18" class="mr-2"><use xlink:href="#align-left"/></svg>
                            Titel
                        </span>
                    </div>

                    <div class="float-left cursor-pointer" @click="addingNewTextSectionAfter(section.sort)">
                        <span title="tekst / afbeelding toevoegen" class="btn btn-o-secondary squished-xs center-y">
                            <svg width="18" height="18" class="mr-2"><use xlink:href="#align-left"/></svg>
                            Tekstblok
                        </span>
                    </div>
                </div>

            </div>

            <div class="flex flex-col" v-if="modulescount+pagescount+setscount > 0">

                <p class="stack-xs">Koppel een ...</p>

                <div class="inline-group-s">
                    <div class="float-left cursor-pointer" v-if="modulescount > 0" @click="addingModuleSectionAfter(section.sort)">
                        <span title="vast blok selecteren" class="btn btn-o-secondary squished-xs center-y">
                            <svg width="18" height="18" class="mr-2"><use xlink:href="#layout"/></svg>
                            Module
                        </span>
                    </div>

                    <div class="float-left cursor-pointer" v-if="pagescount > 0" @click="addingPageSectionAfter(section.sort)">
                        <span title="pagina selecteren" class="btn btn-o-secondary squished-xs center-y">
                            <svg width="18" height="18" class="mr-2"><use xlink:href="#layout"/></svg>
                            Pagina
                        </span>
                    </div>

                    <div class="float-left cursor-pointer" v-if="setscount > 0" @click="addingPageSetSectionAfter(section.sort)">
                        <span title="pagina groep selecteren" class="btn btn-o-secondary squished-xs center-y">
                            <svg width="18" height="18" class="mr-2"><use xlink:href="#layout"/></svg>
                            Paginagroep
                        </span>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</template>
<script>

    export default{
        props:['section', 'modulescount', 'setscount', 'pagescount'],
        data(){
            return {
                active: false,
            }
        },
        methods: {
            addingNewTextSectionAfter(position){
                Eventbus.$emit('addingNewTextSectionAfter',position, this);

                this.active = false;
            },
            addingModuleSectionAfter(position){
                Eventbus.$emit('addingModuleSectionAfter', position, this);

                this.active = false;
            },
            addingPageSetSectionAfter(position){
                Eventbus.$emit('addingPageSetSectionAfter',position, this);

                this.active = false;
            },
            addingPageSectionAfter(position){
                Eventbus.$emit('addingPageSectionAfter',position, this);

                this.active = false;
            },
            addingNewPagetitleSectionAfter(position){
                Eventbus.$emit('addingNewPagetitleSectionAfter',position, this);

                this.active = false;
            },
        }
    }
</script>