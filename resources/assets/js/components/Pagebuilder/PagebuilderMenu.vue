<template>
    <div class="pagebuilder-menu show-on-hover center-y">
        <span v-show="!active" @click="active = true" class="block icon icon-plus-circle menu-trigger"></span>

        <span v-show="active" @click="active = false" class="block icon icon-minus-circle"></span>
        <div v-show="active" class="pagebuilder-menu-items bg-white squished --raised rounded inline-group-s">
            <div class="left pointer" @click="addingNewTextSectionAfter(section.sort)">
                <span title="tekst / afbeelding toevoegen" class="label label-o--secondary center-y"><i class="icon icon-align-left"></i>Text</span>
            </div>
            <div class="left pointer" @click="addingModuleSectionAfter(section.sort)">
                <span title="vast blok selecteren" class="label label-o--secondary center-y">
                    <i class="icon icon-layout"></i>
                    Module
                </span>
            </div>
            <div class="left pointer" @click="addingPageSetSectionAfter(section.sort)">
                <span title="pagina groep selecteren" class="label label-o--secondary center-y">
                    <i class="icon icon-layout"></i>
                    Paginagroep
                </span>
            </div>
            <div class="left pointer" @click="addingPageSectionAfter(section.sort)">
                <span title="pagina selecteren" class="label label-o--secondary center-y">
                    <i class="icon icon-layout"></i>
                    Pagina
                </span>
            </div>
            <div class="left pointer">
                <span title="pagina titel toevoegen" @click="addingNewPagetitleSectionAfter(section.sort)" class="label label-o--secondary center-y">H1</span>
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
                Eventbus.$emit('addingModuleSectionAfter',position, this);

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