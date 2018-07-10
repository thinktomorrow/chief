@chiefformgroup(['field' => 'label'])
    @slot('label', 'Label')
    @slot('description', 'Dit is de tekst die wordt getoond in het menu. Verkies een korte, duidelijke term.')
    <tabs v-cloak>
        @foreach($menuitem->availableLocales() as $locale)
            <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}')}">
                <div class="row">
                    <div class="column-4">
                        <input type="text" name="trans[{{ $locale }}][label]" id="trans-{{ $locale }}-label" placeholder="Menu label" value="{{ old('trans.'.$locale.'.label', $menuitem->getTranslationFor('label', $locale)) }}" class="input inset-s">
                    </div>
                </div>

                <error class="caption text-warning" field="trans.{{ $locale }}.label" :errors="errors.get('trans.{{ $locale }}')"></error>
            </tab>
        @endforeach
    </tabs>
@endchiefformgroup

<section class="formgroup">
    <div class="row gutter-l">
        <div class="formgroup-info column-4">
            <h2 class="formgroup-label">Link</h2>
        </div>
        <div class="formgroup-input column-8">

            <radio-options inline-template :errors="errors" default-type="{{ old('type', $menuitem->type) }}">
                <div>

                    <!-- internal type -->
                    <label class="block stack-xs custom-indicators" for="typeInternal">
                        <input v-on:click="changeType('internal')" {{ (old('type', $menuitem->type) == 'internal') ? 'checked="checked"':'' }}
                        name="type"
                               value="internal"
                               id="typeInternal"
                               type="radio">
                        <span class="custom-radiobutton --primary"></span>
                        <strong>Interne pagina</strong>

                        <div v-if="type == 'internal'" class="stack-xs input-group-prefix relative">
                            <chief-multiselect
                                    name="page_id"
                                    :options='@json($pages)'
                                    selected='@json(old('page_id', $internal_page_id))'
                                    grouplabel="group"
                                    groupvalues="values"
                                    labelkey="label"
                                    valuekey="id"
                            >
                            </chief-multiselect>

                            <error class="caption text-warning" field="page_id" :errors="errors.all()"></error>

                        </div>
                    </label>

                    <!-- custom type -->
                    <label class="block stack custom-indicators" for="typeCustom">
                        <input v-on:click="changeType('custom')" {{ (old('type', $menuitem->type) == 'custom') ? 'checked="checked"':'' }}
                        name="type"
                               value="custom"
                               id="typeCustom"
                               type="radio">
                        <span class="custom-radiobutton --primary"></span>
                        <strong>Of kies een eigen link.</strong>

                        <div v-if="type == 'custom'" class="stack-xs input-group-prefix relative">
                            <tabs v-cloak>
                                @foreach($menuitem->availableLocales() as $locale)
                                    <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.label')}">
                                        <input type="text" name="trans[{{ $locale }}][url]" id="trans-{{ $locale }}-url" placeholder="e.g. https://google.com" value="{{ old('trans.'.$locale.'.url', $menuitem->getTranslationfor('url', $locale)) }}"
                                               class="input inset-s">
                                        <error class="caption text-warning" field="trans.{{ $locale }}.url" :errors="errors.get('trans.{{ $locale }}')"></error>
                                    </tab>
                                @endforeach
                            </tabs>
                        </div>
                    </label>

                    <!-- no link -->
                    <label class="block stack-xs custom-indicators" for="typeNolink">
                        <input v-on:click="changeType('nolink')" {{ (old('type', $menuitem->type) == 'nolink') ? 'checked="checked"':'' }}
                        name="type"
                               value="nolink"
                               id="typeNolink"
                               type="radio">
                        <span class="custom-radiobutton --primary"></span>
                        <strong>Geen link toevoegen aan dit menuitem. </strong>
                    </label>
                </div>
            </radio-options>
        </div>
    </div>
</section>


@chiefformgroup(['field' => 'parent_id'])
    @slot('label', 'Niveau')
    @slot('description', 'Zet dit item op het hoogste niveau of plaats het onder een bestaand.')
    <radio-options inline-template :errors="errors" default-type="{{ !!old('parent_id', $menuitem->parent_id) ? '1' : '0' }}">
        <div>
            <label class="block stack-xs custom-indicators" for="withoutParentId">
                <input v-on:click="changeType('0')" {{ !old('parent_id', $menuitem->parent_id) ? 'checked="checked"':'' }}
                       name="allow_parent"
                       value="0"
                       id="withoutParentId"
                       type="radio">
                <span class="custom-radiobutton --primary"></span>
                <strong>Geef dit item weer op het hoogste niveau.</strong>
            </label>
            <label class="block stack-xs custom-indicators" for="parentId">
                <input v-on:click="changeType('1')" {{ !!old('parent_id', $menuitem->parent_id) ? 'checked="checked"':'' }}
                       name="allow_parent"
                       value="1"
                       id="parentId"
                       type="radio">
                <span class="custom-radiobutton --primary"></span>
                <strong>Selecteer het menuitem waaronder deze zich behoort.</strong>

                <div v-if="type == '1'" class="stack-xs input-group-prefix relative">
                    <chief-multiselect
                            name="parent_id"
                            :options='@json($parents)'
                            selected='@json(old('parent_id', $menuitem->parent_id))'
                            labelkey="label"
                            valuekey="id"
                            placeholder="Kies het bovenliggende menuitem"
                    >
                    </chief-multiselect>
                </div>
            </label>
        </div>
    </radio-options>
@endchiefformgroup


@push('custom-scripts')

<script>
    Vue.component('radio-options',{
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
    });
</script>
@endpush