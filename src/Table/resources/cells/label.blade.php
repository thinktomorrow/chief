<x-chief::table.data>
    <span
        {{ $attributes->merge($getCustomAttributes())->class([
            'label', 'label-xs', 'label-'.$getLayoutType(),
        ]) }}
        title="{{ $getDescription() }}"
    >
        {!! $getTitle() !!}
    </span>
</x-chief::table.data>
