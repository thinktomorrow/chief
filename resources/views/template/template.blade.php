@props([
    'hero' => null,
    'title' => null
])

{{-- This makes the title prop available in parent components --}}
@aware(['title'])

<x-chief::page-layout :title="$title">
    <main id="main" class="flex flex-wrap items-start lg:flex-nowrap">
        {{-- Navigation --}}
        <section class="relative top-0 z-20 max-lg:w-full shrink-0 lg:sticky">
            @include('chief::template.nav.nav')
        </section>

        {{-- Content --}}
        <section id="content" class="w-full">
            @include('chief::template._partials.healthbar')

            <div class="py-4 sm:py-8 lg:py-12">
                @include('chief::template._partials.breadcrumbs')

                @if ($hero)
                    {{ $hero }}
                @else
                    <x-chief::template.hero :title="$title" />
                @endif

                <div v-cloak>
                    {{ $slot }}
                </div>
            </div>

            @include('chief::layout._partials.notifications')
        </section>

        {{-- Sidebar --}}
        <section role="sidebar">
            @include('chief::template._partials.sidebar')
        </section>
    </main>
</x-page-layout>
