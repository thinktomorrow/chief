<url-slugs inline-template :initialfields='@json($fields->toArray())' checkurl="{{ $checkUrl }}">
    <section class="row formgroup stack gutter-l">
        <div class="column-4">
            <h2 class="formgroup-label">Pagina link</h2>
            <p>Bepaal hier de link voor deze pagina. Oude links worden automatisch opgevangen.</p>
        </div>
        <div class="formgroup-input column-8">

            <div class="stack" v-for="field in fields">
                <div v-if="field.show">
                    <label v-if="fields.length > 1" :for="field.key" v-text="field.label"></label>
                    <div class="input-addon stack-xs">
                        <div v-if="field.prepend" class="addon inset-s" v-text="getPrepend(field)"></div>
                        <input @keyup="onSlugChange(field)" v-model="field.value" type="text" :name="field.name" :id="field.key" class="input inset-s" :placeholder="field.placeholder">
                    </div>
                    <div class="text-subtle font-s">
                        <span class="inline-block label label--primary" v-if="field.is_homepage">homepage</span>
                        <a class="inline-block" v-if="field.value" target="_blank" :href="field.prepend + field.value">Bekijk op site</a>
                        <p class="inline-block right text-error" v-if="field.hint" v-html="field.hint"></p>
                    </div>
                </div>
                <div v-else>
                    <label v-text="field.label"></label>
                    <div class="text-subtle font-s">
                        Geen link.
                        <a class="inline-block" @click="showLinkInput(field)">voeg een link toe.</a>
                    </div>
                </div>

                <p class="text-subtle" v-if="field.description" v-html="field.description"></p>
            </div>

        </div>
    </section>
</url-slugs>

@push('custom-components')
    <script>
        Vue.component('url-slugs',{
            props: ['initialfields', 'checkurl'],
            data: function(){
                return {
                    fields: this.initialfields,
                };
            },
            methods: {
                getPrepend: function(field){

                    if(field.value.startsWith('/')) {
                        return field.prepend.slice(0, -1);
                    }

                    return field.prepend;
                },
                onSlugChange: function(field){

                    field.is_homepage = (field.value == '/');

                    this._checkUniqueness(field);
                },
                showLinkInput: function(field){
                    field.show = true;
                },
                _checkUniqueness: _.debounce(function(field){

                    // An empty value is never checked for uniqueness
                    if(!field.value){
                        field.hint = '';
                        return;
                    }

                    let value = field.baseUrlSegment+'/'+field.value;

                    window.axios.post(this.checkurl, {
                        slug: value.replace(/\/\//, '')
                    }).then(function({data}){
                        field.hint = data.hint;
                    });
                }, 600)
            }

        });
    </script>
@endpush
