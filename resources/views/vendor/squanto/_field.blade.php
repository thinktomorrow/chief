@formgroup
    <div class="space-y-2">
        @foreach($locales as $i => $locale)
            @php
                $fieldId = $lineViewModel->id() . '_' . $locale;
            @endphp

            @if($loop->first)
                @slot('label', ucfirst($lineViewModel->label()))
                @slot('description', $lineViewModel->description())
            @endif

            <div class="w-full flex space-x-4">
                <div class="w-1/12">
                    <span class="inline-block label label-primary">{{ $locale }}</span>
                </div>

                <div class="w-11/12">
                    @if($lineViewModel->isFieldTypeTextarea() || $lineViewModel->isFieldTypeEditor())
                        <textarea
                            name="squanto[{{ $lineViewModel->key() }}][{{ $locale }}]"
                            id="{{ $fieldId }}"
                            class="{{ $lineViewModel->isFieldTypeEditor() ? 'redactor-editor' : '' }} w-full"
                        >{!! old('squanto['.$lineViewModel->key().']['.$locale.']', $lineViewModel->value($locale)) !!}</textarea>
                    @else
                        <input
                            type="text"
                            name="squanto[{{ $lineViewModel->key() }}][{{ $locale }}]"
                            id="{{ $fieldId }}"
                            value="{!! old('squanto['.$lineViewModel->key().']['.$locale.']', $lineViewModel->value($locale)) !!}"
                            class="w-full"
                        >
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endformgroup
