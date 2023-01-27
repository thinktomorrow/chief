@props([
    'hero' => null,
    'title' => null
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
        <section id="content" class="w-full">
            @include('chief::templates.page._partials.healthbar')

            <div class="py-4 sm:py-8 lg:py-12">
                @if ($hero)
                    {{ $hero }}
                @else
                    <x-chief::page.hero :title="$title" />
                @endif

                <div v-cloak>
                    {{ $slot }}
                </div>
            </div>

            @include('chief::layout._partials.notifications')
        </section>

        {{-- Sidebar --}}
        <section role="sidebar">
            @include('chief::templates.page._partials.sidebar')
        </section>
    <div>
</x-chief::page.layout>
