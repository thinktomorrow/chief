<script>
export default {
    props: ['checkurl', 'fixedSegment', 'initialValue', 'modelClass', 'modelId'],
    data: function(){
        return {
            value: this.initialValue,
            is_homepage: (this.initialValue === '/'),
            hint: '',
        };
    },
    methods: {
        onInput: function(e){
            this.is_homepage = (e.target.value === '/');
            this._checkUniqueness(e.target.value);
        },
        _checkUniqueness: _.debounce(function(value){

            // An empty value is never checked for uniqueness
            if(!value){
                this.hint = '';
                return;
            }

            const completeSlug = this.fixedSegment+'/'+value;

            window.axios.post(this.checkurl, {
                modelClass: this.modelClass,
                modelId: this.modelId,
                slug: completeSlug.replace(/\/\//, '')
            }).then(({data}) => {
                this.hint = data.hint;
            });
        }, 300)
    }
}
</script>
