<template>
    <div>
        <modal :id="id" type="sidebar"></modal>
    </div>
</template>

<script>

    import Modal from './Modal.vue';

    export default {
        components: {
            'modal' : Modal,
        },
        props: {
            id: { required: true },
        },
    };
</script>