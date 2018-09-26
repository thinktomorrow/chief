@if(count($manager->managedModelDetails()->locales) > 1)
    <tabs>
        @foreach($manager->managedModelDetails()->locales as $locale)
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
    @foreach($manager->managedModelDetails()->locales as $locale)
        @include($viewpath, [
            'key'   => 'trans.'.$locale.'.'.$field->key,
            'name' => 'trans['.$locale.']['.$field->key.']',
            'field' => $field
        ])
    @endforeach
@endif