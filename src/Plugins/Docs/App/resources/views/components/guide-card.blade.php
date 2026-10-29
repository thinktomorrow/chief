@props ([
    'guide',
    'title' => null,
])

<a
    href="{{ route('chief.docs.show', $guide->id) }}"
    {{ $attributes->class(['group border-grey-100 hover:border-grey-200 flex flex-col gap-2 rounded-xl border bg-white p-5 shadow-xs transition-colors']) }}
>
    <span class="text-grey-950 group-hover:text-primary-500 font-medium">{{ $title ?? $guide->title }}</span>

    @if ($guide->description)
        <span class="body text-grey-500 text-sm">{{ $guide->description }}</span>
    @endif

    <span class="text-primary-500 mt-auto pt-2 text-sm font-medium">Lees meer &rarr;</span>
</a>
