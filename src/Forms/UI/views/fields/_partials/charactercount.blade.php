@if (isset($hasCharacterCount) && $hasCharacterCount())
    <div
        wire:ignore
        data-slot="charactercount"
        x-data="characterCount({
                    fieldId: '{{ $getElementId($locale ?? null) }}',
                    max: {{ $getCharacterCount() }},
                })"
        class="border-grey-200 bg-grey-50 relative rounded-b-[0.625rem] border-x border-b px-3 py-1"
        :class="{
            'bg-red-50 border-red-100': characterCount >= max,
            'bg-orange-50 border-orange-100': characterCount >= max - max * 0.1,
            'bg-grey-50 border-grey-200': characterCount < max - max * 0.1,
        }"
    >
        <span
            class="text-grey-500 font-mono text-xs"
            :class="{
                'text-red-500': characterCount >= max,
                'text-orange-500': characterCount >= max - max * 0.1,
                'text-grey-500': characterCount < max - max * 0.1,
            }"
        >
            <span x-text="characterCount"></span>
            / {{ $getCharacterCount() }} karakters
        </span>
    </div>
@endif
