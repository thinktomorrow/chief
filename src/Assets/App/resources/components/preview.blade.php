@php
    $files = $getFiles();
@endphp

<div
    wire:ignore.self
    wire:sortable
    wire:end.stop="reorder($event.target.sortable.toArray())"
    x-init="
        () => {
            // Scroll to bottom of preview container after a file is added
            window.Livewire.on('fileAdded', () => {
                $el.scrollTo({ top: $el.scrollHeight, behavior: 'smooth' })
            })
        }
    "
    @class([
        'max-h-[24rem] divide-y divide-grey-100 overflow-auto rounded-[0.625rem] border-grey-100 shadow-sm',
        'border' => count($files) > 0,
    ])
>
    @foreach ($files as $file)
        @continue(count($files) > 1 && ! $allowMultiple() && $file->isQueuedForDeletion)
        @continue($file->isUploading && $file->isQueuedForDeletion)
        @continue(! $file->isValidated && $file->isQueuedForDeletion)

        @include('chief-assets::_partials.preview-item')
    @endforeach
</div>
