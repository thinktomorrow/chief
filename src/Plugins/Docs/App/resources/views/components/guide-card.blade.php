@props ([
    'guide',
    'title' => null,
])

<a
    href="{{ route('chief.docs.show', $guide->id) }}"
    @class (['border-grey-100 hover:ring-2 hover:border-grey-200 hover:ring-grey-100 flex flex-col gap-2 rounded-xl border bg-white p-4 shadow-xs transition-all bg-white'])
>
    <span class="text-grey-950 font-medium">{{ $title ?? $guide->title }}</span>

    @if ($guide->description)
        <span class="body text-grey-500 text-sm">{{ $guide->description }}</span>
    @endif

    <x-chief::link variant="blue" size="sm" class="hover:no-underline">
        <span>Lees meer</span>
        <x-chief::icon.arrow-right />
    </x-chief::link>
</a>
