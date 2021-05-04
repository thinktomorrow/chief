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

            <div class="flex w-full space-x-4">
                @if(count(config('chief.locales')) > 1)
                    <span class="flex-shrink-0 w-8 px-0 text-sm text-center label label-grey-light">{{ $locale }}</span>
                @endif

                <div class="w-full">
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
                        >
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endformgroup
