@props ([
    'tree',
    'guide' => null,
    'headings' => [],
])

@php
    $openIds = $guide ? $tree->pathIds($guide) : [];
@endphp

{{-- Mobile navigation toggle --}}
<div class="container max-w-full lg:hidden">
    <div class="-ml-2 flex items-center justify-start pt-6 lg:hidden">
        <div data-mobile-navigation-toggle class="hover:bg-grey-200 shrink-0 cursor-pointer rounded-md p-2">
            <x-chief::icon.menu class="text-grey-700 size-6" />
        </div>

        <span class="text-grey-700 py-2 font-semibold">Handleiding</span>
    </div>
</div>

{{-- Navigation --}}
<div
    data-mobile-navigation
    @class ([
        'fixed inset-0 hidden animate-slide-in-nav max-lg:z-10 max-lg:bg-white lg:static lg:flex lg:h-screen lg:animate-none',
        'select-none flex-col justify-between gap-y-9 overflow-y-auto py-6 pr-4 pl-6 lg:sticky lg:top-0',
    ])
>
    <div class="space-y-6">
        <div>
            <div class="flex justify-end lg:hidden">
                <div data-mobile-navigation-toggle class="hover:bg-grey-50 shrink-0 cursor-pointer rounded-md p-2">
                    <x-chief::icon.cancel class="body-dark size-6" />
                </div>
            </div>

            <x-chief::nav.item
                compact
                label="Terug naar Chief"
                :url="route('chief.back.dashboard')"
                :icon="\Illuminate\Support\Facades\Blade::render('<x-chief::icon.arrow-left />')"
            />
        </div>

        <button
            type="button"
            x-data
            x-on:click="$dispatch('open-dialog', { id: 'guide-search' })"
            class="form-input-field text-grey-500 hover:text-grey-700 flex w-full cursor-pointer items-center gap-2 py-1.5 text-left"
        >
            <x-chief::icon.search class="size-5 shrink-0" />
            <span class="grow truncate">Zoek in de handleiding…</span>
            <kbd class="badge badge-xs badge-grey font-sans">⌘K</kbd>
        </button>

        <div>
            <x-chief::nav.item
                compact
                label="Handleiding"
                :url="route('chief.docs.index')"
                :icon="\Illuminate\Support\Facades\Blade::render('<x-chief::icon.book-open />')"
                :is-active="! $guide"
            />

            @foreach ($tree->groups() as $group)
            <div class="mt-6 mb-2 px-2 first:mt-0">
                <span class="body text-grey-500 text-xs">{{ $group->title }}</span>
            </div>

            @foreach ($group->nodes as $node)
            <x-chief-docs::sidebar-item
                :tree="$tree"
                :node="$node"
                :label="$node === $group->section ? 'Inleiding' : $node->title"
                :children="$node === $group->section ? [] : $tree->children($node)"
                :guide="$guide"
                :headings="$headings"
                :open-ids="$openIds"
            />
            @endforeach
            @endforeach
        </div>
    </div>

    <p class="text-grey-400 px-2 text-xs">
        Handleiding bij Chief versie {{ \Composer\InstalledVersions::getVersion('thinktomorrow/chief') }}
    </p>
</div>
