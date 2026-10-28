{{-- PROTOTYPE (wayfinder ticket 08): throwaway guides UI variants. Delete after the decision. --}}
@php
    $withParams = fn (array $params) => request()->url().'?'.http_build_query(array_filter(array_merge(request()->only('variant', 'role', 'empty'), $params), fn ($v) => $v !== null && $v !== ''));
    $pageTitle = $guide && ! $isOverview ? $guide['title'] : 'Handleiding';
@endphp

<x-chief-docs::prototype-template :title="$pageTitle">
    <x-slot name="nav">
        @unless ($empty)
            <div class="pt-docs-nav">@include('chief-docs::prototype.d-nav')</div>
        @endunless
    </x-slot>

    <style>
        .pt-sticky { position: sticky; top: 2rem; max-height: calc(100vh - 4rem); overflow-y: auto; }
        .pt-search { display: flex; align-items: center; gap: .5rem; }
        .pt-search input { border: 0; outline: 0; width: 100%; background: transparent; padding: 0; box-shadow: none; }
        .pt-heading { scroll-margin-top: 1.5rem; position: relative; }
        .pt-anchor { position: absolute; left: -1.75rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; display: grid; place-items: center; border-radius: .375rem; font-size: .75rem; font-weight: 400; background: #f4f4f5; color: #71717a; opacity: 0; text-decoration: none !important; }
        .pt-heading:hover .pt-anchor { opacity: 1; }
        .pt-callout { border-radius: .75rem; padding: .75rem 1rem; margin: 1.25rem 0; border: 1px solid; font-size: .9rem; }
        .pt-callout p { margin: 0; }
        .pt-callout-title { font-weight: 600; margin-bottom: .2rem !important; }
        .pt-callout-green { background: #f0fdf4; border-color: #bbf7d0; color: #14532d; }
        .pt-callout-blue { background: #eff6ff; border-color: #bfdbfe; color: #1e3a8a; }
        .pt-callout-orange { background: #fff7ed; border-color: #fed7aa; color: #7c2d12; }
        .pt-figure { margin: 1.5rem 0; }
        .pt-figure img { border: 1px solid #e4e4e7; border-radius: .75rem; box-shadow: 0 8px 24px -12px rgb(0 0 0 / .2); cursor: zoom-in; width: 100%; }
        .pt-figure figcaption { font-size: .8rem; color: #71717a; margin-top: .5rem; text-align: center; }
        .pt-results a { display: block; padding: .8rem 1rem; border-radius: .6rem; }
        .pt-results a:hover, .pt-results a.is-active { background: #f4f4f5; }
        .pt-results mark { background: #fef08a; color: inherit; border-radius: .15rem; padding: 0 .1rem; }
        .pt-results .pt-crumb { font-size: .72rem; color: #a1a1aa; }
        .pt-results strong { display: block; font-weight: 500; color: #18181b; }
        .pt-results p { font-size: .82rem; color: #52525b; margin-top: .15rem; }
        .pt-palette-backdrop { position: fixed; inset: 0; background: rgb(24 24 27 / .35); z-index: 50; display: flex; justify-content: center; align-items: flex-start; padding-top: 12vh; }
        .pt-palette { width: min(40rem, 92vw); background: #fff; border-radius: 1rem; box-shadow: 0 30px 60px -20px rgb(0 0 0 / .4); overflow: hidden; }
        .pt-lightbox { position: fixed; inset: 0; background: rgb(9 9 11 / .8); z-index: 60; display: flex; align-items: center; justify-content: center; padding: 2rem; cursor: zoom-out; }
        .pt-lightbox img { max-width: 100%; max-height: 100%; border-radius: .75rem; }
        .pt-switcher { position: fixed; bottom: 1rem; left: 50%; transform: translateX(-50%); z-index: 70; display: flex; align-items: center; gap: .5rem; background: #18181b; color: #fff; border-radius: 999px; padding: .4rem .6rem; font: 500 .8rem/1.2 ui-sans-serif, system-ui; box-shadow: 0 10px 30px rgb(0 0 0 / .35); }
        .pt-switcher a { color: #fff; padding: .25rem .55rem; border-radius: 999px; }
        .pt-switcher a:hover { background: #3f3f46; }
        .pt-switcher .pt-sep { width: 1px; height: 1.1rem; background: #52525b; }
        .pt-switcher .is-on { background: #fde047; color: #18181b; }

        /* Shared Protocol-style guide nav + article */
        .pt-d-main { max-width: 48rem; }
        .pt-d-eyebrow { font-size: .8125rem; font-weight: 600; color: var(--color-primary-500, #3b82f6); margin-bottom: .5rem; }
        .pt-d-lead { margin-top: .75rem; font-size: 1.05rem; line-height: 1.6; color: #52525b; text-wrap: pretty; }
        .pt-d-h2 { font-family: var(--font-display, inherit); font-size: 1.15rem; font-weight: 600; color: #09090b; margin: 3rem 0 1rem; padding-top: 1.5rem; border-top: 1px solid #e4e4e7; scroll-margin-top: 1.5rem; }
        .pt-d-h2 .pt-anchor { top: calc(50% + .75rem); }
        .pt-d-prose h2.pt-heading { margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid #e4e4e7; }
        .pt-d-prose h2.pt-heading .pt-anchor { top: calc(50% + .75rem); }
        .pt-d-prose h2.pt-heading:first-child { margin-top: 0; padding-top: 0; border-top: 0; }
        .pt-d-prose h2.pt-heading:first-child .pt-anchor { top: 50%; }
        .pt-d-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(14rem, 1fr)); gap: 1rem; }
        .pt-d-card { display: block; border-radius: .9rem; padding: 1rem 1.1rem; background: #fff; box-shadow: inset 0 0 0 1px #e4e4e7; transition: box-shadow .15s; }
        .pt-d-card:hover { box-shadow: inset 0 0 0 1px #a1a1aa, 0 8px 20px -14px rgb(0 0 0 / .3); }
        .pt-d-card strong { display: block; font-size: .875rem; font-weight: 600; color: #18181b; }
        .pt-d-card p { margin-top: .25rem; font-size: .8125rem; color: #71717a; line-height: 1.45; }
        .pt-d-card span { display: inline-block; margin-top: .75rem; font-size: .8125rem; font-weight: 500; color: var(--color-primary-500, #3b82f6); }
        .pt-d-pager { display: flex; justify-content: space-between; gap: 1rem; margin-top: 3.5rem; padding-top: 1.5rem; border-top: 1px solid #e4e4e7; }
        .pt-d-pager a { display: inline-flex; flex-direction: column; gap: .35rem; }
        .pt-d-pager .text-right a { align-items: flex-end; }
        .pt-d-pager small { display: inline-block; width: fit-content; font-size: .75rem; font-weight: 500; color: #3f3f46; background: #f4f4f5; border-radius: 999px; padding: .15rem .6rem; }
        .pt-d-pager a:hover small { background: #e4e4e7; }
        .pt-d-pager span { font-size: .875rem; font-weight: 500; color: #18181b; }

        /* Docs nav: Chief nav items, a bit tighter, with a primary-coloured bar on the active item */
        .pt-docs-nav .py-1\.5 { padding-top: .2rem; padding-bottom: .2rem; }
        @media (min-width: 1024px) { .pt-docs-sidebar { width: 18rem; } }
        .pt-docs-search { display: flex; width: calc(100% - 1rem); align-items: center; gap: .5rem; margin: .5rem 1rem 0 0; border-radius: .5rem; padding: .4rem .6rem; font-size: .8125rem; color: #71717a; background: #fff; box-shadow: inset 0 0 0 1px #e4e4e7; cursor: text; }
        .pt-docs-search:hover { box-shadow: inset 0 0 0 1px #d4d4d8; color: #3f3f46; }
        .pt-docs-search kbd { font: inherit; font-size: .7rem; color: #a1a1aa; }
        .pt-docs-nav .bg-grey-100 { position: relative; }
        .pt-docs-nav .bg-grey-100::before { content: ''; position: absolute; left: -.625rem; top: .4rem; bottom: .4rem; width: 2px; border-radius: 2px; background: var(--color-primary-500, #3b82f6); }
        .pt-docs-nav .bg-grey-100 a { font-weight: 500; }

        .pt-docs-nav .mt-6 { margin-top: 1.25rem; }
        .pt-docs-nav .mb-2 { margin-bottom: .25rem; }

        /* Docs mode: article on a full-height white surface next to the docs nav */
        .pt-docs-main { background: #fff; border-left: 1px solid #e4e4e7; min-height: 100vh; padding: 2.5rem 3rem 6rem; }
        @media (max-width: 767px) { .pt-docs-main { padding: 1.5rem; } }
        .pt-docs-main .pt-d-main { margin: 0 auto; }
        .pt-docs-main .pt-d-card { background: #fafafa; box-shadow: inset 0 0 0 1px #f4f4f5; }
        .pt-docs-main .pt-d-card:hover { background: #fff; box-shadow: inset 0 0 0 1px #d4d4d8, 0 8px 20px -14px rgb(0 0 0 / .3); }
    </style>

    @if ($empty)
        @include('chief-docs::prototype.empty')
    @else
        @include('chief-docs::prototype.d-article')
    @endif

    @unless ($empty)
        @include('chief-docs::prototype.palette')
    @endunless

    {{-- Lightbox for screenshots --}}
    <div x-data="{ src: null }" x-on:click.window="if ($event.target.closest('[data-pt-zoom]')) { $event.preventDefault(); src = $event.target.closest('[data-pt-zoom]').href }" x-on:keydown.escape.window="src = null">
        <template x-if="src"><div class="pt-lightbox" x-on:click="src = null"><img :src="src" alt=""></div></template>
    </div>

    {{-- Prototype switcher: not part of the design --}}
    <div class="pt-switcher">
        <span>Prototype</span>
        <span class="pt-sep"></span>
        <a href="{{ $withParams(['role' => $role === 'admin' ? 'author' : 'admin']) }}" title="Wissel rol (beheer is roles: [admin])">rol: {{ $role }}</a>
        <a href="{{ $withParams(['empty' => $empty ? null : 1]) }}" @class(['is-on' => $empty]) title="Lege staat">leeg</a>
    </div>
</x-chief-docs::prototype-template>
