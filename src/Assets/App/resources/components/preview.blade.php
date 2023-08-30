@php
    $files = $getFiles();
@endphp

<div
    wire:sortable
    wire:start="$set('isReordering', true)"
    wire:end.stop="reorder($event.target.sortable.toArray())"
    x-init="() => {
        // Scroll to bottom of preview container after a file is added
        Livewire.on('fileAdded', () => {
            $el.scrollTo({ top: $el.scrollHeight, behavior: 'smooth' });
        });
    }"
    @class([
        'overflow-auto divide-y rounded-lg border-grey-200 divide-grey-200 max-h-[24rem] shadow-sm',
        'border' => count($files) > 0
    ])
>
    @foreach ($files as $file)
        @continue(count($files) > 1 && !$allowMultiple() && $file->isQueuedForDeletion)
        @continue($file->isUploading && $file->isQueuedForDeletion)
        @continue(!$file->isValidated && $file->isQueuedForDeletion)

        @include('chief-assets::_partials.preview-item')
    @endforeach
</div>
