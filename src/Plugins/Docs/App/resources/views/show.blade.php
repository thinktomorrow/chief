<x-chief::page.template :title="$guide->title" container="lg">
    <x-slot name="header">
        @include ('chief-docs::breadcrumbs')
    </x-slot>

    <x-chief::window>
        <div
            @class ([
                'prose prose-dark prose-spacing max-w-none',
                '[&_.heading-permalink]:badge [&_.heading-permalink]:badge-xs [&_.heading-permalink]:badge-grey',
                '[&_.heading-permalink]:ml-2 [&_.heading-permalink]:align-middle [&_.heading-permalink]:no-underline',
                '[&_.heading-permalink]:opacity-0 [&_.heading-permalink]:transition-opacity',
                '[&_:is(h2,h3):hover_.heading-permalink]:opacity-100',
            ])
        >
            {!! $content !!}
        </div>
    </x-chief::window>
</x-chief::page.template>
