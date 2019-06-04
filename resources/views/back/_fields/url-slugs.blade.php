<url-slugs inline-template :initialfields='@json($fields->toArray())' :conflictingfields='@json($fields->toArray())'>
    <section class="row formgroup stack gutter-l bg-white">
        <div class="column-4">
            <h2 class="formgroup-label">Link naar de pagina</h2>
            <p>Bepaal hier welke link er gebruikt wordt voor deze pagina.</p>
            <div class="stack-xs font-s" v-for="field in fields">
                <span class="bold" v-text="field.label"></span><br>
                <span v-text="field.prepend"></span>
                <span v-text="field.value"></span>
            </div>
        </div>
        <div class="formgroup-input column-8">

            <div class="stack" v-for="field in fields">
                <label :for="field.key" v-text="field.label"></label>
                <div class="input-addon stack-xs">
                    <div v-if="field.prepend" class="addon inset-s" v-text="field.prepend"></div>
                    <input v-model="field.value" type="text" :name="field.name" :id="field.key" class="input inset-s" :placeholder="field.placeholder">
                </div>
                <p class="text-subtle" v-if="field.description" v-html="field.description"></p>
            </div>

            <div class="stack bg-error inset" v-for="field in fields">
                <label :for="field.key" v-text="field.label"></label>
                <div class="input-addon stack-xs">
                    <div v-if="field.prepend" class="addon inset-s" v-text="field.prepend"></div>
                    <input v-model="field.value" type="text" :name="field.name" :id="field.key" class="input inset-s" :placeholder="field.placeholder">
                </div>
                <p class="text-subtle" v-if="field.description" v-html="field.description"></p>
            </div>

        </div>
    </section>
</url-slugs>


@push('custom-components')
    <script>
        Vue.component('url-slugs',{
            props: ['initialfields'],
            data: function(){
                return {
                    fields: this.initialfields,
                    showLocalizedFields: this.hasLocalizedValues(),
                };
            },
            mounted: function(){

            },
            methods: {
                hasLocalizedValues: function(){
                    return false;
                }
            }

        });
    </script>
@endpush
