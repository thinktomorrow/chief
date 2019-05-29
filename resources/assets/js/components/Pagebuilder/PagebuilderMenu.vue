<template>
    <div class="absolute flex justify-start items-center w-full h-8 show-on-hover center-y" style="z-index:1;bottom:-24px;left:-14px">
        
        <span v-show="!active" @click="active = true" class="block menu-trigger cursor-pointer">
            <svg width="24" height="24"><use xlink:href="#plus"/></svg>
        </span>

        <span v-show="active" @click="active = false" class="block cursor-pointer">
            <svg width="24" height="24"><use xlink:href="#min"/></svg>
        </span>

        <div v-show="active" class="pagebuilder-menu-items float-right ml-4 relative bg-white inset --raised rounded flex inline-group-l">
            
            <div class="flex flex-col">

                <p class="stack-xs">Creëer een ...</p>

                <div class="inline-group-s">
                    <div class="float-left cursor-pointer">
                        <span title="pagina titel toevoegen" @click="addingNewPagetitleSectionAfter(section.sort)" class="label label-o--secondary center-y">
                            <svg width="18" height="18" class="mr-2"><use xlink:href="#align-left"/></svg>
                            Titel
                        </span>
                    </div>

                    <div class="float-left cursor-pointer" @click="addingNewTextSectionAfter(section.sort)">
                        <span title="tekst / afbeelding toevoegen" class="label label-o--secondary center-y">
                            <svg width="18" height="18" class="mr-2"><use xlink:href="#align-left"/></svg>
                            Tekstblok
                        </span>
                    </div>
                </div>

            </div>

            <div class="flex flex-col">

                <p class="stack-xs">Koppel een ...</p>

                <div class="inline-group-s">
                    <div class="float-left cursor-pointer" @click="addingModuleSectionAfter(section.sort)">
                        <span title="vast blok selecteren" class="label label-o--secondary center-y">
                            <svg width="18" height="18" class="mr-2"><use xlink:href="#layout"/></svg>
                            Module
                        </span>
                    </div>

                    <div class="float-left cursor-pointer" @click="addingPageSectionAfter(section.sort)">
                        <span title="pagina selecteren" class="label label-o--secondary center-y">
                            <svg width="18" height="18" class="mr-2"><use xlink:href="#layout"/></svg>
                            Pagina
                        </span>
                    </div>

                    <div class="float-left cursor-pointer" @click="addingPageSetSectionAfter(section.sort)">
                        <span title="pagina groep selecteren" class="label label-o--secondary center-y">
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
        props:['section'],
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