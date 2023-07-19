@php
    $files = $getFiles();
@endphp
<div
    wire:sortable
    wire:start="$set('isReordering', true)"
    wire:end.stop="reorder($event.target.sortable.toArray())"
    @class([
        'overflow-auto divide-y rounded-lg border-grey-200 divide-grey-200 max-h-[24rem] shadow-sm',
        'border' => count($files) > 0
    ])
>

    @foreach ($files as $file)
        @continue(count($files) > 1 && !$allowMultiple() && $file->isQueuedForDeletion)
        @continue($file->isUploading && $file->isQueuedForDeletion)
        @continue(!$file->isValidated && $file->isQueuedForDeletion)

        <div wire:sortable.item="{{ $file->id }}" class="flex gap-4 p-2 {{ !$file->isValidated ? 'opacity-50' : '' }}">
            @include('chief-assets::_partials.preview-' . ($file->isExternalAsset ? 'external' : 'local'))
        </div>
    @endforeach
</div>

@push('custom-scripts-after-vue')
    <script>
        @this.on('fileAdded', () => {
            console.log('file added.');
            // TODO: Tijs: scroll to bottom of this file container so the last added files are shown
            // document.getElementById('test').scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });
        })
    </script>
@endpush
