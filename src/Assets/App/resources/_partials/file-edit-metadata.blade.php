@php
    use Carbon\Carbon;
@endphp

<div class="space-y-0.5 text-grey-500 text-sm">
    <dl class="flex justify-between gap-1">
        <dt>Bestandsgrootte</dt>
        <dd class="text-right">{{ $previewFile->humanReadableSize }}</dd>
    </dl>

    @if($previewFile->isImage())
        <dl class="flex justify-between gap-1">
            <dt>Afmetingen</dt>
            <dd class="text-right">{{ $previewFile->width }}x{{ $previewFile->height }}</dd>
        </dl>
    @endif

    <dl class="flex justify-between gap-1">
        <dt>Bestandsextensie</dt>
        <dd class="text-right">{{ $previewFile->extension }}</dd>
    </dl>

    @if($previewFile && $previewFile->createdAt)
        <dl class="flex justify-between gap-1">
            <dt>Toegevoegd op</dt>
            <dd class="text-right">{{ Carbon::createFromTimestamp($previewFile->createdAt)->format('d/m/Y H:i') }}</dd>
        </dl>

        @if($previewFile->updatedAt !== $previewFile->createdAt)
            <dl class="flex justify-between gap-1">
                <dt>Laatst aangepast</dt>
                <dd class="text-right">{{ Carbon::createFromTimestamp($previewFile->updatedAt)->format('d/m/Y H:i') }}</dd>
            </dl>
        @endif
    @endif
</div>
