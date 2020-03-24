<script>

    import Fragment from "./Fragment";

    export default {
        components: {Fragment},
        props: ['emptyFragment', 'existingFragments', 'errors'],
        render(){
            return this.$scopedSlots.default({
                fragments: this.fragments,
                actions: {
                    duplicateFragment: this.duplicateFragment,
                },
                errors: this.errors,
            });
        },
        data: function () {
            return {
                fragments: this.existingFragments,
            };
        },
        created: function(){
            // this.;
        },
        methods: {
            duplicateFragment: function(){

                const nextId = this.fragments.length;
                let newFragment = JSON.parse(JSON.stringify(this.emptyFragment));

                for(var k in newFragment.fields) {
                    let field = newFragment.fields[k];

                    // Replace the name and id attribute with the expected identifier
                    field.content = field.content.replace(/\[0\]/g, '['+nextId+']');
                    field.content = field.content.replace(/\.0\./g, '.'+nextId+'.');

                    newFragment.fields[k] = field;
                    newFragment.key = this._randomHash();
                }

                this.fragments.push(newFragment);
            },
            _randomHash(){
                // http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript
                return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            },
        }
    }
</script>
