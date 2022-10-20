<x-chief::table.data>
    <span
        {{ $attributes->merge($getCustomAttributes())->class([
            'label',
            'label-xs',
            'label-'. $getLayoutType()->value,
        ]) }}
        title="{{ $getHint() }}"
    >
        {!! $getValue() !!}
    </span>
</x-chief::table.data>
