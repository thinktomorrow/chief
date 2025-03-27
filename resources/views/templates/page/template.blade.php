@props([
    'container' => '2xl',
    'title' => null,
    'header' => null,
    'sidebar' => null,
])

{{--
    Explanation "min-w-0" class:
    If the min-width property isn't set on these flex items, they will overflow their parent flex container if they happen to contain a table (go figure).
    Dev sessions saved by this comment: 2.
--}}
<x-chief::page.layout :title="$title">
    <div class="flex max-lg:flex-col lg:min-h-screen lg:items-stretch">
        {{-- Navigation --}}
        <div class="w-full shrink-0 lg:w-64">
            @include('chief::templates.page.nav.nav')
        </div>

        <div
            {{-- DO NOT DELETE min-w-0 (cfr. comment above) --}}
            @class([
                'container min-w-0 grow space-y-6 py-8',
                'max-w-screen-sm' => $container === 'sm',
                'max-w-screen-md' => $container === 'md',
                'max-w-screen-lg' => $container === 'lg',
                'max-w-screen-xl' => $container === 'xl',
                'max-w-screen-2xl' => $container === '2xl',
            ])
        >
            {{-- Header --}}
            @if ($header)
                <div {{ $header->attributes }}>
                    {{ $header }}
                </div>
            @else
                <x-chief::page.header />
            @endif

            <div class="flex gap-6 max-md:flex-col">
                {{-- Main content --}}
                {{-- DO NOT DELETE min-w-0 (cfr. comment above) --}}
                <div class="min-w-0 grow">
                    {{ $slot }}
                </div>

                {{-- Sidebar --}}
                @if ($sidebar)
                    <section
                        {{
                            $sidebar->attributes->merge([
                                'role' => 'sidebar',
                                'class' => 'w-full shrink-0 md:w-64 lg:w-80 xl:w-96 2xl:w-[26rem]',
                            ])
                        }}
                    >
                        {{ $sidebar }}
                    </section>
                @endif
            </div>
        </div>
    </div>
</x-chief::page.layout>
