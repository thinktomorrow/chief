@php
    use Thinktomorrow\Chief\Sites\ChiefSites;
    use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;
@endphp

<div class="flex w-full flex-wrap justify-between gap-x-6 gap-y-3 sm:flex-nowrap">
    <div class="w-full shrink-0 space-y-1 sm:w-64">
        <p class="h6 h1-dark font-medium">
            {{ ucfirst(str_replace('_', ' ', $lineViewModel->label())) }}
        </p>

        @if ($lineViewModel->description())
            <p class="body text-sm text-grey-500">
                {{ $lineViewModel->description() }}
            </p>
        @endif
    </div>

    <div class="w-full space-y-2">
        @foreach ($locales as $i => $locale)
            @php
                $fieldId = $lineViewModel->id() . '_' . $locale;
            @endphp

            <div class="flex w-full gap-2">
                @if (count(ChiefLocales::locales()) > 1)
                    <div class="flex w-8 shrink-0 items-center justify-center rounded-md bg-grey-50 p-2">
                        <span class="text-xs uppercase text-grey-500">{{ $locale }}</span>
                    </div>
                @endif

                <div class="form-light w-full">
                    @if ($lineViewModel->isFieldTypeTextarea() || $lineViewModel->isFieldTypeEditor())
                        <x-chief::input.textarea
                            name="squanto[{{ $lineViewModel->key() }}][{{ $locale }}]"
                            id="{{ $fieldId }}"
                            class="{{ $lineViewModel->isFieldTypeEditor() ? 'redactor-editor' : '' }} w-full"
                        >
                            {!! old('squanto[' . $lineViewModel->key() . '][' . $locale . ']', $lineViewModel->value($locale)) !!}
                        </x-chief::input.textarea>
                    @else
                        <x-chief::input.text
                            name="squanto[{{ $lineViewModel->key() }}][{{ $locale }}]"
                            id="{{ $fieldId }}"
                            value="{!! old('squanto['.$lineViewModel->key().']['.$locale.']', $lineViewModel->value($locale)) !!}"
                        />
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
