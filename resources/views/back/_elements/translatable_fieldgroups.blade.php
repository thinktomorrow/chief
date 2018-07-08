@if(count($model->availableLocales()) > 1)
    <tabs>
        @foreach($model->availableLocales() as $locale)
            <tab id="{{ $locale }}-translatable-fields" name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}')}">
                @include('chief::back._elements.dynamic-form')
            </tab>
        @endforeach
    </tabs>
@else
    @foreach($model->availableLocales() as $locale)
        @include('chief::back._elements.dynamic-form')
    @endforeach
@endif