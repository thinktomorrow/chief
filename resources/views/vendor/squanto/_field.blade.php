@php
    use Thinktomorrow\Chief\Sites\ChiefSites;
    use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;
@endphp

<x-chief::form.preview
    size="lg"
    :label="ucfirst(str_replace('_', ' ', $lineViewModel->label()))"
    :description="$lineViewModel->description()"
>
    <div class="space-y-3">
        @foreach ($locales as $i => $locale)
            @php
                $fieldId = $lineViewModel->id() . '_' . $locale;
            @endphp

            <div class="flex w-full gap-2">
                @if (count(ChiefLocales::locales()) > 1)
                    <div class="flex w-8 shrink-0 items-center justify-center rounded-lg bg-grey-50 p-2">
                        <span class="text-xs font-medium uppercase text-grey-500">{{ $locale }}</span>
                    </div>
                @endif

                <div class="form-light w-full">
                    @if ($lineViewModel->isFieldTypeTextarea() || $lineViewModel->isFieldTypeEditor())
                        <x-chief::form.input.textarea
                            name="squanto[{{ $lineViewModel->key() }}][{{ $locale }}]"
                            id="{{ $fieldId }}"
                            @class(['w-full', 'redactor-editor' => $lineViewModel->isFieldTypeEditor()])
                        >
                            {!! old('squanto[' . $lineViewModel->key() . '][' . $locale . ']', $lineViewModel->value($locale)) !!}
                        </x-chief::form.input.textarea>
                    @else
                        <x-chief::form.input.text
                            name="squanto[{{ $lineViewModel->key() }}][{{ $locale }}]"
                            id="{{ $fieldId }}"
                            value="{!! old('squanto['.$lineViewModel->key().']['.$locale.']', $lineViewModel->value($locale)) !!}"
                        />
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-chief::form.preview>
