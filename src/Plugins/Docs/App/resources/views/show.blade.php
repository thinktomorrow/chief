<x-chief::page.template :title="$guide->title" container="lg">
    <x-slot name="header">
        @include ('chief-docs::breadcrumbs')
    </x-slot>

    <div
        x-data="{
            lightbox: { src: '', alt: '' },
            openLightbox(image) {
                this.lightbox = { src: image.currentSrc || image.src, alt: image.alt };
                this.$dispatch('open-dialog', { id: 'guide-lightbox' });
            },
        }"
    >
        <x-chief::window>
            <div
                x-on:click="$event.target.matches('img[data-guide-media]') && openLightbox($event.target)"
                @class ([
                    'prose prose-dark prose-spacing max-w-none',
                    '[&_.heading-permalink]:badge [&_.heading-permalink]:badge-xs [&_.heading-permalink]:badge-grey',
                    '[&_.heading-permalink]:ml-2 [&_.heading-permalink]:align-middle [&_.heading-permalink]:no-underline',
                    '[&_.heading-permalink]:opacity-0 [&_.heading-permalink]:transition-opacity',
                    '[&_:is(h2,h3):hover_.heading-permalink]:opacity-100',
                    '[&_[data-guide-media]]:w-full [&_[data-guide-media]]:rounded-lg [&_[data-guide-media]]:border [&_[data-guide-media]]:border-grey-100',
                    '[&_img[data-guide-media]]:cursor-zoom-in',
                ])
            >
                {!! $content !!}
            </div>
        </x-chief::window>

        <x-chief::dialog.modal id="guide-lightbox" size="2xl" x-on:keydown.escape.window="close()">
            <x-slot name="header" class="flex items-start justify-between gap-4 p-4">
                <p class="body text-grey-500 mt-1.5" x-text="lightbox.alt"></p>

                <x-chief::button size="sm" variant="grey" type="button" x-on:click.stop="close()" class="ml-auto">
                    <x-chief::icon.cancel />
                </x-chief::button>
            </x-slot>

            <img x-bind:src="lightbox.src" x-bind:alt="lightbox.alt" class="mx-auto max-h-[calc(100vh-12rem)] w-auto" />
        </x-chief::dialog.modal>
    </div>
</x-chief::page.template>
