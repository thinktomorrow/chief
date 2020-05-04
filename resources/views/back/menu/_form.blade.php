@formgroup(['field' => 'label'])
    @slot('label', 'Label')
    @slot('description', 'Dit is de tekst die wordt getoond in het menu. Verkies een korte, duidelijke term.')
    @slot('isRequired', true)
    @if(count($menuitem->availableLocales()) > 1)
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
    @else
        @foreach($menuitem->availableLocales() as $locale)
            <div class="row">
                <div class="column-4">
                    <input type="text" name="trans[{{ $locale }}][label]" id="trans-{{ $locale }}-label" placeholder="Menu label" value="{{ old('trans.'.$locale.'.label', $menuitem->getTranslationFor('label', $locale)) }}" class="input inset-s">
                </div>
            </div>
            <error class="caption text-warning" field="trans.{{ $locale }}.label" :errors="errors.get('trans.{{ $locale }}')"></error>
        @endforeach
    @endif
    <input type="hidden" name="menu_type" value="{{$menuitem->menu_type}}">
@endformgroup

<section class="formgroup">
    <div class="row gutter-l">
        <div class="formgroup-info column-4">
            <h2>Link</h2>
        </div>
        <div class="formgroup-input column-8">

            <radio-options inline-template :errors="errors" default-type="{{ old('type', $menuitem->type) }}">
                <div>

                    <!-- internal type -->
                    <label class="block stack custom-indicators" for="typeInternal">
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
                            @if(count($menuitem->availableLocales()) > 1)
                            <tabs v-cloak>
                                @foreach($menuitem->availableLocales() as $locale)
                                    <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.label')}">
                                        <input type="text" name="trans[{{ $locale }}][url]" id="trans-{{ $locale }}-url" placeholder="e.g. https://google.com" value="{{ old('trans.'.$locale.'.url', $menuitem->getTranslationfor('url', $locale)) }}"
                                               class="input inset-s">
                                        <error class="caption text-warning" field="trans.{{ $locale }}.url" :errors="errors.get('trans.{{ $locale }}')"></error>
                                    </tab>
                                @endforeach
                            </tabs>
                            @else
                                @foreach($menuitem->availableLocales() as $locale)
                                        <input type="text" name="trans[{{ $locale }}][url]" id="trans-{{ $locale }}-url" placeholder="e.g. https://google.com" value="{{ old('trans.'.$locale.'.url', $menuitem->getTranslationfor('url', $locale)) }}"
                                            class="input inset-s">
                                        <error class="caption text-warning" field="trans.{{ $locale }}.url" :errors="errors.get('trans.{{ $locale }}')"></error>
                                @endforeach
                            @endif
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

@if(count($parents) > 0)
    @formgroup(['field' => 'parent_id'])
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
    @endformgroup
@endif

@if($menuitem->id && ! $menuitem->siblings()->isEmpty())
    @formgroup(['field' => 'order'])
        @slot('label', 'Sortering')
        @slot('description', 'Sortering van dit menu item op het huidige niveau')
        <div class="row">
            <div class="column-1">
                <input type="number" name="order" id="order" placeholder="Menu order" value="{{ old('order', $menuitem->order) }}" class="input inset-s text-center">
            </div>
        </div>
        <div class="stack">

            <div class="border border-grey-100 rounded bg-white">
                <div class="inset-s" style="border-bottom:1px solid #f5f5f5">
                    <span class="bold">Huidige sortering op dit niveau:</span>
                </div>
                @foreach($menuitem->siblingsIncludingSelf() as $sibling)
                    <div class="inset-s" style="border-bottom:1px solid #f5f5f5;{{ $sibling->id == $menuitem->id ? 'background-color:#f5f5f5;' : '' }}">
                        <span class="bold inline-s">{{ $sibling->order }}</span>
                        <span>{{ $sibling->label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endformgroup
@endif
