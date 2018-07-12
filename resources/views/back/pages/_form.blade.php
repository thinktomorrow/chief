<tabs>
    <tab name="Inhoud">
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">Titel van de {{ $page->collectionDetails()->singular }}</h2>
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
                <a class="btn btn-o-primary">Opslaan als draft</a>
                <a @click="showModal('publication-page')" class="btn btn-o-secondary">Plan je pagina in</a>
            </div>
        </section>
    </tab>
</tabs>
