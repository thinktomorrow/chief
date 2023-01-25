<script>
export default {
    props: ['errors', 'defaultType'],
    data: function(){
        return {
            type: this.defaultType,
        };
    },
    methods:{
        changeType: function(type){
            this.type = type;
        },
    }
};
</script>
