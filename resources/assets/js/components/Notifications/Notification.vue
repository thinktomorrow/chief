<template>
    <div v-show="isVisible" class="pl-8 pr-16 py-4 bg-white rounded-r-lg shadow-lg relative border-l-2" :class="border">
        <div class="mb-1">
            <h4 v-if="this.title">{{ this.title }}</h4>
        </div>

        <p v-if="this.description" class="text-grey-500">{{ this.description }}</p>

        <div @click="hideNotification" class="absolute top-0 right-0 m-2 p-1 text-grey-500 rounded-full cursor-pointer bg-grey-100 hover:bg-grey-50 transition duration-150 ease-in-out">
            <svg width="16" height="16"><use xlink:href="#x"></use></svg>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            title: String,
            description: String,
            type: String,
        },
        data() {
            return {
                isVisible: true,
                border: this.setBorderByType()
            }
        },
        methods: {
            hideNotification: function() {
                this.isVisible = false;
            },
            setBorderByType: function() {
                switch(this.type) {
                    case 'success':
                        return 'border-success';
                        break;

                    default:
                        return 'border-information';
                        break;
                }
            }
        }
    }
</script>
