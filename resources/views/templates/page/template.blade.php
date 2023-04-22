@props([
    'hero' => null,
    'title' => null,
    'sidebar' => null,
])

{{-- This makes the title prop available in parent components, e.g page.layout metatags --}}
@aware(['title'])

<x-chief::page.layout :title="$title">
    <div class="flex flex-wrap items-start lg:flex-nowrap">
        {{-- Navigation --}}
        <section class="relative top-0 z-20 max-lg:w-full shrink-0 lg:sticky">
            @include('chief::templates.page.nav.nav')
        </section>

        {{-- Content --}}
        {{-- If min-width isn't set, this section will overflow the flex container if it contains a table (go figure) --}}
        <section id="content" class="w-full min-w-0">
            @include('chief::templates.page._partials.healthbar')

            <div class="py-4 sm:py-8">
                @if ($hero)
                    {{ $hero }}
                @else
                    <x-chief::page.hero :title="$title" />
                @endif

                <div v-cloak>
                    {{ $slot }}
                </div>
            </div>

            @include('chief::templates.page._partials.notifications')
        </section>

        @if ($sidebar)
            <section class="w-full h-full min-h-screen bg-white border-l md:w-192 border-grey-200">
                {{ $sidebar }}
            </section>
        @endif

        {{-- Sidebar --}}
        <section role="sidebar">
            @include('chief::templates.page._partials.sidebar')
        </section>
    <div>
</x-chief::page.layout>
