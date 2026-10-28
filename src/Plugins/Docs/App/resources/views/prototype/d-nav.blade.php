@php
    $loose = array_values(array_filter($tree, fn ($n) => ! $n['children']));
    $sections = array_values(array_filter($tree, fn ($n) => $n['children']));
@endphp

<x-chief::nav.item
    label="Handleiding"
    :url="url('admin/docs-prototype').'?'.http_build_query(request()->only('role'))"
    :is-active="$isOverview"
    icon='<svg viewBox="0 0 24 24" fill="none"><path d="M20.5 16.9v-12c0-1-1-1.6-1.9-1.3C15.4 4.8 12.6 4.4 12 6.5c-.6-2.1-3.4-1.7-6.6-2.9-.9-.3-1.9.3-1.9 1.3v12c0 .6.4 1.2 1 1.4 3.2 1.2 6.4.8 7.5 3.2 1.1-2.4 4.3-2 7.5-3.2.6-.2 1-.8 1-1.4Z" stroke="currentColor" stroke-width="1.5"/><path d="M12 6.5V21" stroke="currentColor" stroke-width="1.5"/></svg>'
/>

<button type="button" class="pt-docs-search" x-data x-on:click="$dispatch('pt-open-palette')">
    <x-chief::icon.search class="size-4 shrink-0" />
    <span class="grow text-left">Zoek in de handleiding…</span>
    <kbd>⌘K</kbd>
</button>

<div class="pt-docs-tree">
<div class="mt-6 mb-2 px-2"><span class="body text-grey-500 text-xs">Aan de slag</span></div>
@foreach ($loose as $node)
    @include('chief-docs::prototype.d-nav-item', ['node' => $node, 'label' => null])
@endforeach

@foreach ($sections as $section)
    <div class="mt-6 mb-2 px-2"><span class="body text-grey-500 text-xs">{{ $section['title'] }}</span></div>
    @if ($section['body'] !== null)
        @include('chief-docs::prototype.d-nav-item', ['node' => ['children' => []] + $section, 'label' => 'Inleiding'])
    @endif
    @foreach ($section['children'] as $child)
        @include('chief-docs::prototype.d-nav-item', ['node' => $child, 'label' => null])
    @endforeach
@endforeach
</div>
