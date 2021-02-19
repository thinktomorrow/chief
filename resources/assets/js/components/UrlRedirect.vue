
<script>
export default {
    props: ['fieldProp', 'removeurl'],
    data: function(){
        return {
            removed: false,
        };
    },
    methods: {
        remove: function(){

            const self = this;

            window.axios.post(this.removeurl, {
                _method: 'DELETE',
            }).then(function({data}){
                self.removed = true;
            });
        }
    }
}
</script>
