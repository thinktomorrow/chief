@php
    use Thinktomorrow\Chief\Sites\ChiefSites;
    use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;
@endphp

<div class="@container">
    <div class="flex flex-wrap items-start gap-4 @2xl:flex-nowrap">
        <div class="w-full shrink-0 space-y-2 @2xl:w-64">
            <p class="@lg:max-w-48 mt-0.5 text-sm/5 font-medium text-grey-500">
                {{ ucfirst(str_replace('_', ' ', $lineViewModel->label())) }}
            </p>

            @if ($lineViewModel->description())
                <x-chief::form.description class="text-grey-700">
                    {{ $lineViewModel->description() }}
                </x-chief::form.description>
            @endif
        </div>

        <div class="w-full grow space-y-3">
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
    </div>
</div>
