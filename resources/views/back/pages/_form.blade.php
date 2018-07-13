<tabs>
    <tab name="Inhoud">
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">{{ $page->collectionDetails()->singular }} titel</h2>
            </div>
            <div class="formgroup-input column-8">
                @if(count($page->availableLocales()) > 1)
                    <tabs v-cloak>
                        @foreach($page->availableLocales() as $locale)
                            <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.title')}">
                                @include('chief::back.pages._partials.title-form')
                            </tab>
                        @endforeach
                    </tabs>
                @else
                    @foreach($page->availableLocales() as $locale)
                        @include('chief::back.pages._partials.title-form')
                    @endforeach
                @endif
            </div>
        </section>

        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">PAGEBUILDER</h2>
            </div>
            <div class="formgroup-input column-8">

                <section class="stack block inset-s" style="border-left:2px solid lightgreen">
                    <tabs>
                        @foreach($page->availableLocales() as $key => $locale)
                            <tab v-cloak id="{{ $locale }}-translatable-fields" name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}')}">
                                <textarea data-editor class="inset-s" name="trans[{{ $locale }}][{{ $key }}]" id="trans-{{ $locale }}-{{ $key }}" cols="10" rows="5">{{ old('trans.'.$locale.'.'. $key,$page->translateForForm($locale,$key)) }}</textarea>
                            </tab>
                        @endforeach
                    </tabs>
                </section>


                <section class="stack-s block inset-s" style="border-left:2px solid lightgreen">
                    MODULE SELECTIE....
                </section>

                <section class="stack-s block inset-s" style="border-left:2px solid lightgreen">
                    <tabs>
                        @foreach($page->availableLocales() as $key => $locale)
                            <tab v-cloak id="{{ $locale }}-translatable-fields" name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}')}">
                                <textarea data-editor class="inset-s" name="trans[{{ $locale }}][{{ $key }}]" id="trans-{{ $locale }}-{{ $key }}" cols="10" rows="5">{{ old('trans.'.$locale.'.'. $key,$page->translateForForm($locale,$key)) }}</textarea>
                            </tab>
                        @endforeach
                    </tabs>
                </section>
            </div>
        </section>

        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">Inhoud</h2>
            </div>
            <div class="formgroup-input column-8">
                @include('chief::back._elements.translatable_fieldgroups', [
                    'model' => $page,
                ])
            </div>
        </section>

        @foreach($page->mediaFields() as $media)

            <?php

                $viewPath = (isset($media['is_document']) && $media['is_document'])
                        ? 'chief::back._elements.mediagroup-documents'
                        : 'chief::back._elements.mediagroup-images';

            ?>

            @include($viewPath, [
                'group' => $media['type'],
                'files' => $images[$media['type']],
                'label' => $media['label'],
                'description' => $media['description'],
            ])
        @endforeach

        <a href="#seo" class="btn btn-o-primary right">volgende</a>
    </tab>
    <tab name="Seo">
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">Zoekmachines</h2>
                <p class="caption">Titel en omschrijving van het pagina zoals het in search engines (o.a. google) wordt weergegeven.</p>
            </div>
            <div class="formgroup-input column-7">
                @if(count($page->availableLocales()) > 1)
                    <tabs v-cloak>
                        @foreach($page->availableLocales() as $locale)
                            <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.seo_title')}">
                                @include('chief::back.pages._partials.seo-form')
                            </tab>
                        @endforeach
                    </tabs>
                @else
                    @foreach($page->availableLocales() as $locale)
                        @include('chief::back.pages._partials.seo-form')
                    @endforeach
                @endif
                <label for="seo-title"><i>Preview</i></label>
                <div class="panel seo-preview --border inset bc-success">
                    <h2 class="text-information">SEO Titel</h2>
                    <span class="link text-success">https://crius-group.com/page</span>
                    <p class="caption">preview van description tekst hier</p>
                </div>
            </div>
        </section>
        <a href="#modules" class="btn btn-o-primary right">volgende</a>
    </tab>

    <tab name="Relaties">

        {{-- MODULES --}}
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">Gerelateerde onderwerpen</h2>
                <p class="caption">Bij het pagina kan je enkele gerelateerde onderwerpen koppelen. <br>Deze worden automatisch onderaan de pagina pagina getoond.</p>
            </div>
            <div class="formgroup-input column-8">
                <h4>Voeg een nieuwe relatie toe</h4>
                <chief-multiselect
                name="relations"
                :options='@json($relations)'
                selected='@json($page->existingRelationIds->toArray())'
                :multiple="true"
                grouplabel="group"
                groupvalues="values"
                labelkey="label"
                valuekey="id"
                placeholder="..."
                >
                </chief-multiselect>
            </div>
            <div class="column-12 text-right">
                <a class="btn btn-o-primary">Opslaan</a>
            </div>
        </section>
    </tab>
</tabs>


@push('custom-scripts')
<script>
    Vue.component('chief-permalink', {
        props: ['root', 'defaultPath'],
        data: function(){
            return {
                path: this.defaultPath || '',
                editMode: false,
            };
        },
        computed: {
            fullUrl: function(){
                return this.root + '/' + this.path;
            }
        },
        render: function(){
            return this.$scopedSlots.default({
                data: this.$data,
                fullUrl: this.fullUrl
            });
        }
    });
</script>
@endpush
