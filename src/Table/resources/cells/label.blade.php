<x-chief::table.data>
    <span
        {{ $attributes->merge($getCustomAttributes())->class([
            'label', 'label-xs', 'label-'.$getLayoutType()->value,
        ]) }}
        title="{{ $getDescription() }}"
    >
        {!! $getTitle() !!}
    </span>
</x-chief::table.data>
