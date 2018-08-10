<template>
    <div class="pagebuilder-menu show-on-hover">
        <span v-show="!active" @click="active = true" class="block icon icon-plus-circle menu-trigger"></span>
        <span v-show="active" @click="active = false" class="block icon icon-minus-circle"></span>
        <div v-show="active" class="pagebuilder-menu-items bg-white squished --raised rounded inline-group-s">
            <div class="block left pointer" @click="addingNewTextSectionAfter(section.sort)">
                <span title="tekst / afbeelding toevoegen" class="label label-o--secondary center-y"><i class="icon icon-align-left"></i>Text</span>
            </div>
            <div class="block left pointer" @click="addingModuleSectionAfter(section.sort)">
                <span title="vast blok selecteren" class="label label-o--secondary center-y">
                    <i class="icon icon-layout"></i>
                    Module
                </span>
            </div>
            <!-- NON FUNCTIONAL -->
            <div class="block left pointer">
                <span title="titel selecteren" class="label label-o--secondary center-y"><i class="icon icon-award"></i>Title</span>
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
        }
    }
</script>