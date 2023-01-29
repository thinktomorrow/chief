<div class="flex flex-wrap justify-between w-full sm:flex-nowrap gap-y-3 gap-x-6">
    <div class="w-full space-y-1 sm:w-64 shrink-0">
        <p class="font-medium h6 h1-dark">
            {{ ucfirst(str_replace('_', ' ', $lineViewModel->label())) }}
        </p>

        @if ($lineViewModel->description())
            <p class="text-sm body text-grey-500">
                {{ $lineViewModel->description() }}
            </p>
        @endif
    </div>

    <div class="w-full space-y-2">
        @foreach($locales as $i => $locale)
            @php
                $fieldId = $lineViewModel->id() . '_' . $locale;
            @endphp

            <div class="flex w-full gap-2">
                @if(count(config('chief.locales')) > 1)
                    <div class="flex items-center justify-center w-8 p-2 rounded-md shrink-0 bg-grey-50">
                        <span class="text-xs uppercase text-grey-500">{{ $locale }}</span>
                    </div>
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
</div>
