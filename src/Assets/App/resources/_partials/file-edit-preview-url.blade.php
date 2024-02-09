<div class="flex items-start justify-between gap-2">
    <a
        href="{{ $previewFile->getUrl() }}"
        title="{{ $previewFile->getUrl() }}"
        target="_blank"
        rel="noopener"
        class="leading-5"
    >
        <x-chief::link underline class="break-all">
            {{ $previewFile->getUrl() }}
        </x-chief::link>
    </a>

    <div class="flex items-start gap-2 shrink-0">
        <x-chief::copy-button :content="$previewFile->getUrl()" successContent="Link naar bestand gekopieerd!">
            <x-chief::link>
                <svg><use xlink:href="#icon-link"></use></svg>
            </x-chief::link>
        </x-chief::copy-button>

        <a
            href="{{ $previewFile->getUrl() }}"
            title="{{ $previewFile->getUrl() }}"
            target="_blank"
            rel="noopener"
            class="leading-5"
        >
            <x-chief::link>
                <svg><use xlink:href="#icon-external-link"></use></svg>
            </x-chief::link>
        </a>
    </div>
</div>
