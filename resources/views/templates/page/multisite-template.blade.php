@props([
    'hero' => null,
    'sidebar' => null,
    'container' => '2xl',
])

<x-chief::page.layout>
    <div class="flex max-lg:flex-col lg:min-h-screen lg:items-stretch">
        {{-- Navigation --}}
        <div class="w-full shrink-0 lg:w-64 2xl:w-96">
            @include('chief::templates.page.nav.nav')
        </div>

        <div
            @class([
                'container grow space-y-8 py-12',
                'max-w-screen-lg' => $container === 'lg',
                'max-w-screen-xl' => $container === 'xl',
                'max-w-screen-2xl' => $container === '2xl',
            ])
        >
            {{-- Hero --}}
            @if ($hero)
                <div>
                    {{ $hero }}
                </div>
            @endif

            <div class="flex gap-8 max-md:flex-col">
                {{-- Main content --}}
                <div class="grow">
                    {{ $slot }}
                </div>

                {{-- Sidebar --}}
                @if ($sidebar)
                    <div class="w-full shrink-0 md:w-64 2xl:w-96">
                        {{ $sidebar }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- TODO: Remove this once we have a proper sidebar for the multisite template --}}
    <section role="sidebar">
        @include('chief::templates.page._partials.sidebar')
    </section>
</x-chief::page.layout>
