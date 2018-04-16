<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Paginainhoud</h2>
        <p class="caption">Dit is de artikelnaam zoals ze ook wordt weergegeven voor uw bezoekers.</p>
    </div>
    <div class="formgroup-input column-8">

        <translation-tabs>
            @foreach($article->availableLocales() as $locale)

                <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.name')}">
                    <div class="stack-s">
                        <label for="trans-{{ $locale }}-title">Titel</label>
                        <input type="text" name="trans[{{ $locale }}][title]" id="trans-{{ $locale }}-title" class="input inset-s" placeholder="Titel" value="{{ old('trans.'.$locale.'.title',$article->translateForForm($locale,'title')) }}">
                        <span class="stack text-default"><b>Permalink:</b> https://crius-group.com/<b>artikelnaam</b><button>edit</button></span>
                    </div>

                    <error class="caption text-warning" field="trans.{{ $locale }}.name" :errors="errors.get('trans.{{ $locale }}')"></error>

                    <div class="stack">
                        <label for="trans-{{ $locale }}-content">Tekst</label>
                        <textarea class="redactor inset-s" name="trans[{{ $locale }}][content]" id="trans-{{ $locale }}-content" cols="30" rows="10">{{ old('trans.'.$locale.'.content',$article->translateForForm($locale,'content')) }}</textarea>
                    </div>
                </tab>

            @endforeach
        </translation-tabs>

    </div>
</section>
<hr>

<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Modules</h2>
        <p class="caption">Kies hier de modules die jij wil koppelen</p>
    </div>
    <div class="formgroup-input column-7">
        <label for="seo-title">Modules</label>

        <div class="stack">
            <label for="seo-description">Inhoud</label>
            <textarea id="seo-description" cols="30" rows="20" class="input redactor inset-s" placeholder="Beschrijving" type="text" required=""></textarea>
        </div>

    </div>
</section>
<hr>
{{-- SEO --}}
<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Zoekmachines</h2>
        <p class="caption">Titel en omschrijving van het artikel zoals het in search engines (o.a. google) wordt weergegeven.</p>
    </div>
    <div class="formgroup-input column-7">
        <label for="seo-title">Titel</label>
        <input id="seo-title" class="input inset-s" placeholder="Seo titel" type="text" required="">
        <div class="stack">
            <label for="seo-description">Beschrijving</label>
            <textarea id="seo-description" cols="30" rows="5" class="input inset-s" placeholder="Seo beschrijving" type="text" required=""></textarea>
        </div>

        <label for="seo-title"><i>Preview</i></label>
        <div class="panel seo-preview --border inset bc-success">
            <h2 class="text-information">SEO Titel</h2>
            <span class="link text-success">https://crius-group.com/article</span>
            <p class="caption">preview van description tekst hier</p>
        </div>
    </div>
</section>
<hr>
{{-- Zichtbaarheid --}}
<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Publicatie</h2>
        <p class="caption">Lorem ipsum</p>
    </div>
    <div class="formgroup-input column-7">
        <div class="custom-indicators">
            <label for="switch-1">Zichtbaar</label>
            <input class="switch switch-primary" id="switch-1" type="checkbox" checked/>
            <label class="custom-switch switch-btn" for="switch-1"></label>
        </div>

        <div class="stack">
            <label for="publication-date">Zichtbaar vanaf</label>
            <input type="datetime-local" name="publication-date" class="squished">
        </div>
    </div>
</section>
<hr>