<template>
    <div>
        <mkiha-modal :id="id" type="sidebar"></mkiha-modal>
    </div>
</template>

<script>

    import Modal from './Modal.vue';

    export default {
        components: {
            'mkiha-modal' : Modal,
        },
        props: {
            id: { required: true },
        },
    };
</script>