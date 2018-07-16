<template>
    <div class="pagebuilder-menu">
        <span v-show="!active" @click="active = true" class="icon icon-plus-circle"></span>
        <span v-show="active" @click="active = false" class="icon icon-minus-circle"></span>
        <div v-show="active" class="pagebuilder-menu-items">
            <span @click="addingNewTextSectionAfter(section.sort)">
                <span title="tekst / afbeelding toevoegen" class="icon icon-align-left"></span>
            </span>
            <span @click="addingModuleSectionAfter(section.sort)">
                <span title="vast blok selecteren" class="icon icon-clipboard"></span>
            </span>
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