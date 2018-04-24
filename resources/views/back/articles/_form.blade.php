<tabs>
    <tab name="Inhoud">
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">Paginainhoud</h2>
                <p class="caption">Dit is de artikelnaam zoals ze ook wordt weergegeven voor uw bezoekers.</p>
            </div>
            <div class="formgroup-input column-8">

                <tabs>
                    @foreach($article->availableLocales() as $locale)

                        <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.title')}">
                            <div class="stack-s">
                                <label for="trans-{{ $locale }}-title">Titel</label>
                                <input type="text" name="trans[{{ $locale }}][title]" id="trans-{{ $locale }}-title" class="input inset-s" placeholder="Titel" value="{{ old('trans.'.$locale.'.title',$article->translateForForm($locale,'title')) }}">
                                <span class="stack text-default"><b>Permalink:</b> https://crius-group.com/<b>artikelnaam</b><button>edit</button></span>
                            </div>

                            <error class="caption text-warning" field="trans.{{ $locale }}.title" :errors="errors.get('trans.{{ $locale }}')"></error>

                            <div class="stack">
                                <label for="trans-{{ $locale }}-content">Korte omschrijving</label>
                                <textarea class="inset-s" name="trans[{{ $locale }}][description]" id="trans-{{ $locale }}-description" cols="10" rows="5">{{ old('trans.'.$locale.'.content',$article->translateForForm($locale,'content')) }}</textarea>
                            </div>
                            <div class="stack">
                                <label for="trans-{{ $locale }}-content">Tekst</label>
                                <textarea class="redactor inset-s" name="trans[{{ $locale }}][content]" id="trans-{{ $locale }}-content" cols="10" rows="20">{{ old('trans.'.$locale.'.content',$article->translateForForm($locale,'content')) }}</textarea>
                            </div>
                        </tab>

                    @endforeach
                </tabs>
            </div>
        </section>
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">Featured image</h2>
                <p class="caption">Kies hier de afbeelding die bij het artikel hoort</p>
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
                <p class="caption">Titel en omschrijving van het artikel zoals het in search engines (o.a. google) wordt weergegeven.</p>
            </div>
            <div class="formgroup-input column-7">
                <tabs>
                    @foreach($article->availableLocales() as $locale)

                        <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.seo_title')}">
                            <div class="stack-s">
                                <label for="trans-{{ $locale }}-seo_title">Seo titel</label>
                                <input type="text" name="trans[{{ $locale }}][seo_title]" id="trans-{{ $locale }}-seo_title" class="input inset-s" placeholder="Seo titel" value="{{ old('trans.'.$locale.'.seo_title',$article->translateForForm($locale,'seo_title')) }}">
                                <span class="stack text-default"><b>Permalink:</b> https://crius-group.com/<b>artikelnaam</b><button>edit</button></span>
                            </div>

                            <error class="caption text-warning" field="trans.{{ $locale }}.seo_title" :errors="errors.get('trans.{{ $locale }}')"></error>

                            <div class="stack">
                                <label for="trans-{{ $locale }}-seo_description">Seo omschrijving</label>
                                <textarea class="inset-s" name="trans[{{ $locale }}][seo_description]" id="trans-{{ $locale }}-seo_description" cols="30" rows="10">{{ old('trans.'.$locale.'.seo_description',$article->translateForForm($locale,'seo_description')) }}</textarea>
                            </div>
                        </tab>

                    @endforeach
                </tabs>

                <label for="seo-title"><i>Preview</i></label>
                <div class="panel seo-preview --border inset bc-success">
                    <h2 class="text-information">SEO Titel</h2>
                    <span class="link text-success">https://crius-group.com/article</span>
                    <p class="caption">preview van description tekst hier</p>
                </div>
            </div>
        </section>
        <a href="#modules" class="btn btn-o-primary right">volgende</a>
    </tab>

    <tab name="Modules">

        {{-- MODULES --}}
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">Artikels koppelen</h2>
                <p class="caption">Kies hier de artikels die je wil koppelen</p>
            </div>
            <div class="formgroup-input column-7">
                <div class="stack">
                    <chief-multiselect
                    name="artikels"
                    :options="[{'label': 'Artikels', 'values': ['Artikel 1','Artikel 2','Artikel 3']}]"
                    :multiple="true"
                    grouplabel="label"
                    groupvalues="values"
                    >
                    </chief-multiselect>
                </div>
            </div>
            <div class="column-12 text-right">
                <a class="btn btn-o-primary">Opslaan als draft</a>
                <a @click="showModal('publication-article')" class="btn btn-o-secondary">Plan je artikel in</a>
            </div>
        </section>
    </tab>
</tabs>
