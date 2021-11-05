<div
    data-conditional
    {{-- data-conditional-trigger-type="{{ $type ?? null }}" --}}
    {{-- data-conditional-data="{{ '' }}" --}}
    data-conditional-trigger-key="{{ $triggerKey }}"
    data-conditional-trigger-value="{{ $triggerValue }}"
>
    {!! $slot !!}
</div>
