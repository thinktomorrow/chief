@if(count($model->availableLocales()) > 1)
    <tabs>
        @foreach($model->availableLocales() as $locale)
            <tab v-cloak id="{{ $locale }}-translatable-fields" name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}')}">
                @include('chief::back._fields.translatable_customfield')
            </tab>
        @endforeach
    </tabs>
@else
    @foreach($model->availableLocales() as $locale)
        @include('chief::back._fields.translatable_customfield')
    @endforeach
@endif