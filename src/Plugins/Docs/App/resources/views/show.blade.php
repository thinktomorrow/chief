@php
    $section = \Illuminate\Support\Arr::last($tree->ancestors($guide));
@endphp

<x-chief::page.template :title="$guide->title" container="full">
    <x-slot name="nav">
        <x-chief-docs::sidebar :tree="$tree" :guide="$guide" :headings="$renderedGuide->headings" />
    </x-slot>

    <article
        class="grow"
        x-data="{
            lightbox: { src: '', alt: '' },
            openLightbox(image) {
                this.lightbox = { src: image.currentSrc || image.src, alt: image.alt };
                this.$dispatch('open-dialog', { id: 'guide-lightbox' });
            },
        }"
    >
        <div class="mx-auto max-w-4xl space-y-4 px-6 py-8 lg:px-12">
            @if ($section)
                <p class="text-primary-500 text-sm/6 font-medium">{{ $section->title }}</p>
            @endif

            <h1 class="h1 h1-dark text-pretty">{{ $guide->title }}</h1>

            @if ($guide->description)
                <p class="body text-grey-500 text-lg">{{ $guide->description }}</p>
            @endif

            <div
                x-on:click="$event.target.matches('img[data-guide-media]') && openLightbox($event.target)"
                @class ([
                    'prose prose-dark prose-spacing mt-10 max-w-none',
                    '[&_h2]:border-grey-100 [&_h2]:border-t [&_h2]:pt-8',
                    '[&_.heading-permalink]:badge [&_.heading-permalink]:badge-xs [&_.heading-permalink]:badge-grey',
                    '[&_.heading-permalink]:ml-2 [&_.heading-permalink]:align-middle [&_.heading-permalink]:no-underline',
                    '[&_.heading-permalink]:opacity-0 [&_.heading-permalink]:transition-opacity',
                    '[&_:is(h2,h3):hover_.heading-permalink]:opacity-100',
                    '[&_[data-guide-media]]:w-full [&_[data-guide-media]]:rounded-lg [&_[data-guide-media]]:border [&_[data-guide-media]]:border-grey-100',
                    '[&_img[data-guide-media]]:cursor-zoom-in',
                ])
            >
                {!! $renderedGuide->content !!}
            </div>

            @php
                $childGuides = $tree->guidesAmong($tree->children($guide));
            @endphp

            @if ($childGuides)
                <section class="mt-16 space-y-6">
                    <h2 class="h3 h3-dark">In dit onderdeel</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($childGuides as $childGuide)
                            <x-chief-docs::guide-card :guide="$childGuide" />
                        @endforeach
                    </div>
                </section>
            @endif

            <div class="mt-16">
                <x-chief-docs::pager :previous="$tree->previous($guide)" :next="$tree->next($guide)" />
            </div>
        </div>

        <x-chief::dialog.modal id="guide-lightbox" size="2xl" x-on:keydown.escape.window="close()">
            <x-slot name="header" class="flex items-start justify-between gap-4 p-4">
                <p class="body text-grey-500 mt-1.5" x-text="lightbox.alt"></p>

                <x-chief::button size="sm" variant="grey" type="button" x-on:click.stop="close()" class="ml-auto">
                    <x-chief::icon.cancel />
                </x-chief::button>
            </x-slot>

            <img x-bind:src="lightbox.src" x-bind:alt="lightbox.alt" class="mx-auto max-h-[calc(100vh-12rem)] w-auto" />
        </x-chief::dialog.modal>

        <livewire:chief-docs::guide-search />
    </article>
</x-chief::page.template>
