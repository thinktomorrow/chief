<script>
    export default {
        props: ['item', 'errors'],
        render(){
            return this.$scopedSlots.default({
                fragment: this.fragment,
                errors: this.errors,
                deleteFragment: this.deleteFragment,
                deleted: this.deleted,
            });
        },
        data: function () {
            return {
                fragment: this.item,
                deleted: false,
            };
        },
        methods: {
            deleteFragment: function(){
                this.deleted = true;
            }
        }
    }
</script>
