<div data-slot="control" class="space-y-2">
    @php
        $id = \Illuminate\Support\Str::random();
    @endphp

    <label wire:key="{{ $id }}" for="{{ $id }}" class="flex items-start justify-between gap-x-6">
        @if ($getOptionLabel() || $getOptionDescription())
            <div class="mt-0.5 grow">
                @if ($getLabel())
                    <x-chief::form.label
                        class="[&>[data-slot=label-text]]:text-grey-700 [&>[data-slot=label-text]]:font-normal"
                    >
                        {!! $getOptionLabel() !!}
                    </x-chief::form.label>
                @else
                    <x-chief::form.label :required="$isRequired()" :translatable="$showsLocaleIndicatorInForm()">
                        {!! $getOptionLabel() !!}
                    </x-chief::form.label>
                @endif

                <x-chief::form.description>
                    {!! $getOptionDescription() !!}
                </x-chief::form.description>
            </div>
        @endif

        <div class="shrink-0">
            <x-chief::form.input.checkbox
                id="{{ $id }}"
                name="{{ $getName($locale ?? null) . '[]' }}"
                value="1"
                :checked="in_array(1, (array) $getActiveValue($locale ?? null))"
                class="hidden appearance-none"
                :attributes="$attributes->merge($getCustomAttributes())->merge([$getWireModelType() => $getWireModelValue($locale ?? null)])"
            />

            <span class="form-input-toggle shrink-0"></span>
        </div>
    </label>
</div>
