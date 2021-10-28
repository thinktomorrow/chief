@if(count($field->getLocales()) > 1)
    <div data-vue-fields>
        <tabs>
            @foreach($field->getLocales() as $locale)
                <tab v-cloak id="{{ $locale }}-translatable-fields" name="{{ $locale }}">
                    @include('chief::manager.fields.form.types.'.$field->getViewKey(), [
                        'key' => 'trans.'.$locale.'.'.$field->getKey(),
                        'name' => $field->getName($locale),
                        'field' => $field
                    ])
                </tab>
            @endforeach
        </tabs>
    </div>
@else
    @foreach($field->getLocales() as $locale)
        @include('chief::manager.fields.form.types.'.$field->getViewKey(), [
            'key' => 'trans.'.$locale.'.'.$field->getKey(),
            'name' => $field->getName($locale),
            'field' => $field
        ])
    @endforeach
@endif
