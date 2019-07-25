@if(count($field->locales) > 1)
    <tabs>
        @foreach($field->locales as $locale)
            <tab v-cloak id="{{ $locale }}-translatable-fields" name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}')}">
                @include($formElementView, [
                    'key'   => 'trans.'.$locale.'.'.$field->key,
                    'name' => $field->translateName($locale),
                    'field' => $field
                ])
            </tab>
        @endforeach
    </tabs>
@else
    @foreach($field->locales as $locale)
        @include($formElementView, [
            'key'   => 'trans.'.$locale.'.'.$field->key,
            'name' => $field->translateName($locale),
            'field' => $field
        ])
    @endforeach
@endif

