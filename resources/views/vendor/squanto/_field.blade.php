<div class="space-y-2">
    @foreach($locales as $i => $locale)
        @php
            $fieldId = $lineViewModel->id() . '_' . $locale;
        @endphp

        @if($loop->first)
            <div>
                <p class="font-medium display-base display-dark">
                    {{ ucfirst(str_replace('_', ' ', $lineViewModel->label())) }}
                    {{ $lineViewModel->description() }}
                </p>
            </div>
        @endif

        <div class="flex w-full gap-4">
            @if(count(config('chief.locales')) > 1)
                <span class="inline-flex items-center justify-center w-8 p-0 mt-1 shrink-0 label label-grey label-sm">
                    {{ $locale }}
                </span>
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
