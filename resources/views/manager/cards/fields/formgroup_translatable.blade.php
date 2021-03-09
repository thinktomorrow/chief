@if(count($field->getLocales()) > 1)
    <tabs>
        @foreach($field->getLocales() as $locale)
            <tab v-cloak id="{{ $locale }}-translatable-fields" name="{{ $locale }}">
{{--            <tab v-cloak id="{{ $locale }}-translatable-fields" name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}')}">--}}
                @include('chief::manager.fieldtypes.'.$field->getType()->get(), [
                    'key'   => 'trans.'.$locale.'.'.$field->getKey(),
                    'name' => $field->getName($locale),
                    'field' => $field
                ])
            </tab>
        @endforeach
    </tabs>
@else
    @foreach($field->getLocales() as $locale)
        @include('chief::manager.fieldtypes.'.$field->getType()->get(), [
            'key'   => 'trans.'.$locale.'.'.$field->getKey(),
            'name'  => $field->getName($locale),
            'field' => $field
        ])
    @endforeach
@endif
