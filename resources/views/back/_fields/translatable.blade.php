@if(count($field->locales) > 1)
    <tabs>
        @foreach($field->locales as $locale)
            <tab v-cloak id="{{ $locale }}-translatable-fields" name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}')}">
                @include($viewpath, [
                    'key'   => 'trans.'.$locale.'.'.$field->key,
                    'name' => 'trans['.$locale.']['.$field->key.']',
                    'field' => $field
                ])
            </tab>
        @endforeach
    </tabs>
@else
    @foreach($field->locales as $locale)
        @include($viewpath, [
            'key'   => 'trans.'.$locale.'.'.$field->key,
            'name' => 'trans['.$locale.']['.$field->key.']',
            'field' => $field
        ])
    @endforeach
@endif