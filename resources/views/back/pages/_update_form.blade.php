<tabs>
    <tab name="Inhoud">
        <section class="row formgroup stack gutter-l">
            <div class="formgroup-input column-12">
                <tabs>
                    @foreach($page->availableLocales() as $locale)

                        <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.title')}">
                            <div class="stack-s">
                                <label for="trans-{{ $locale }}-title">Titel</label>
                                <input type="text" name="trans[{{ $locale }}][title]" id="trans-{{ $locale }}-title" class="input inset-s" placeholder="Titel" value="{{ old('trans.'.$locale.'.title', $page->translateForForm($locale,'title')) }}">
                                <span class="stack inline-block text-default">
                                    <b>Permalink:</b> https://crius-group.com/
                                    <input class="inset-s" type="text" name="trans[{{$locale}}][slug]" value="{{ old('trans.'.$locale.'.slug', $page->translateForForm($locale,'slug')) }}">
                                </span>
                                <error class="caption text-warning" field="trans.{{ $locale }}.slug" :errors="errors.get('trans.{{ $locale }}')"></error>
                            </div>

                            <error class="caption text-warning" field="trans.{{ $locale }}.title" :errors="errors.get('trans.{{ $locale }}')"></error>

                            <div class="stack">
                                <label for="trans-{{ $locale }}-description">Korte omschrijving</label>
                                <textarea data-editor class="inset-s" name="trans[{{ $locale }}][description]" id="trans-{{ $locale }}-description" cols="10" rows="5">{{ prepareForRedactor(old('trans.'.$locale.'.description',$page->translateForForm($locale,'description'))) }}</textarea>
                            </div>

                            <error class="caption text-warning" field="trans.{{ $locale }}.description" :errors="errors.get('trans.{{ $locale }}')"></error>

                            <div class="stack">
                                <label for="trans-{{ $locale }}-content">Tekst</label>
                                <textarea data-editor class="inset-s" name="trans[{{ $locale }}][content]" id="trans-{{ $locale }}-content" cols="10" rows="20">{{ prepareForRedactor(old('trans.'.$locale.'.content',$page->translateForForm($locale,'content'))) }}</textarea>
                            </div>

                            <error class="caption text-warning" field="trans.{{ $locale }}.content" :errors="errors.get('trans.{{ $locale }}')"></error>
                        </tab>

                    @endforeach
                </tabs>
            </div>
        </section>
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">Featured image</h2>
                <p class="caption">Kies hier de afbeelding die bij het pagina hoort</p>
            </div>
            <div class="formgroup-input column-7">
                <div class="input-group">
                    <label for="file">Upload</label>
                    <label class="custom-file">
                        <input type="file" id="file">
                        <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
                    </label>
                </div>
            </div>
        </section>
        <a href="#seo" class="btn btn-o-primary right">volgende</a>
    </tab>
    <tab name="Seo">
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">Zoekmachines</h2>
                <p class="caption">Titel en omschrijving van het pagina zoals het in search engines (o.a. google) wordt weergegeven.</p>
            </div>
            <div class="formgroup-input column-7">
                <tabs>
                    @foreach($page->availableLocales() as $locale)

                        <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.seo_title')}">
                            <div class="stack-s">
                                <label for="trans-{{ $locale }}-seo_title">Seo titel</label>
                                <input type="text" name="trans[{{ $locale }}][seo_title]" id="trans-{{ $locale }}-seo_title" class="input inset-s" placeholder="Seo titel" value="{{ old('trans.'.$locale.'.seo_title',$page->translateForForm($locale,'seo_title')) }}">
                            </div>

                            <error class="caption text-warning" field="trans.{{ $locale }}.seo_title" :errors="errors.get('trans.{{ $locale }}')"></error>

                            <div class="stack">
                                <label for="trans-{{ $locale }}-seo_description">Seo omschrijving</label>
                                <textarea class="inset-s" name="trans[{{ $locale }}][seo_description]" id="trans-{{ $locale }}-seo_description" cols="30" rows="10">{{ old('trans.'.$locale.'.seo_description',$page->translateForForm($locale,'seo_description')) }}</textarea>
                            </div>
                        </tab>

                    @endforeach
                </tabs>

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
