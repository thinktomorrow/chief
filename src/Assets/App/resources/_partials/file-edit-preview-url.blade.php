@if ($previewFile->getUrl())
    <div class="flex items-start justify-between gap-2">
        <x-chief::link
            size="sm"
            href="{{ $previewFile->getUrl() }}"
            title="{{ $previewFile->getUrl() }}"
            target="_blank"
            rel="noopener"
            class="break-all"
        >
            {{ $previewFile->getUrl() }}
        </x-chief::link>

        <div class="flex shrink-0 items-start gap-2">
            <x-chief::link
                x-copy="{ content: '{{ $previewFile->getUrl() }}', successContent: 'Link naar bestand gekopieerd!' }"
            >
                <x-chief::icon.link />
            </x-chief::link>

            <x-chief::link
                size="sm"
                href="{{ $previewFile->getUrl() }}"
                title="{{ $previewFile->getUrl() }}"
                target="_blank"
                rel="noopener"
            >
                <x-chief::icon.link-square />
            </x-chief::link>
        </div>
    </div>
@endif
