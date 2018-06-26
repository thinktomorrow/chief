<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Titel</h2>
    </div>
    <div class="formgroup-input column-8">
        <tabs>
            @foreach($module->availableLocales() as $locale)

                <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.title')}">
                    <div class="stack-s">
                        <label for="trans-{{ $locale }}-title">Titel</label>
                        <input type="text" name="trans[{{ $locale }}][title]" id="trans-{{ $locale }}-title" class="input inset-s" placeholder="Titel" value="{{ old('trans.'.$locale.'.title', $module->translateForForm($locale,'title')) }}">
                    </div>

                    <error class="caption text-warning" field="trans.{{ $locale }}.title" :errors="errors.get('trans.{{ $locale }}')"></error>
                </tab>

            @endforeach
        </tabs>
    </div>
</section>

<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Inhoud</h2>
    </div>
    <div class="formgroup-input column-8">
        @include('chief::back._elements.translatable_fieldgroups', [
            'model' => $module,
        ])
    </div>
</section>

@foreach($module->mediaFields() as $mediaType)
    @include('chief::back._elements.mediagroup', [
        'group' => $mediaType['type'],
        'files' => $images[$mediaType['type']],
        'label' => $mediaType['label'],
        'description' => $mediaType['description'],
    ])
@endforeach
