<tabs>
    <tab name="Inhoud">
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">Paginainhoud</h2>
                <p class="caption">Dit is de paginanaam zoals ze ook wordt weergegeven voor uw bezoekers.</p>
            </div>
            <div class="formgroup-input column-8">

                <tabs>
                    @foreach($page->availableLocales() as $locale)

                        <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.title')}">
                            <div class="stack-s">
                                <label for="trans-{{ $locale }}-title">Titel</label>
                                <input type="text" name="trans[{{ $locale }}][title]" id="trans-{{ $locale }}-title" class="input inset-s" placeholder="Titel" value="{{ old('trans.'.$locale.'.title', $page->translateForForm($locale,'title')) }}">
                            </div>

                            <error class="caption text-warning" field="trans.{{ $locale }}.title" :errors="errors.get('trans.{{ $locale }}')"></error>

                            <div class="stack">
                                <label for="trans-{{ $locale }}-content">Tekst</label>
                                <textarea class="redactor inset-s" name="trans[{{ $locale }}][content]" id="trans-{{ $locale }}-content" cols="10" rows="20">{{ old('trans.'.$locale.'.content',$page->translateForForm($locale,'content')) }}</textarea>
                            </div>

                            <error class="caption text-warning" field="trans.{{ $locale }}.content" :errors="errors.get('trans.{{ $locale }}')"></error>
                        </tab>

                    @endforeach
                </tabs>
            </div>
        </section>
    </tab>
</tabs>
