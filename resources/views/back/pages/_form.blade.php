<tabs>
    <tab name="Inhoud">
        <section class="row formgroup stack gutter-l">
            <div class="formgroup-input column-8">
                <h2 class="formgroup-label">{{ $page->collectionDetails()->singular }} titel</h2>

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
        <section class="formgroup stack">
            <page-builder
                    :locales="{ 'nl': 'nl', 'fr': 'fr' }"
                    :default-sections='@json($sections)'
                    :modules='@json($relations)'>
            </page-builder>
        </section>

        <a href="#custom-fields" class="btn btn-o-primary right">volgende</a>
    </tab>
    <tab name="Afbeeldingen & gegevens" id="custom-fields">

        @if(count($page->translatableFields()) > 0)
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
        @endif

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
        <button type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
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
