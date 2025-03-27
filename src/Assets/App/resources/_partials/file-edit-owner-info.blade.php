{{-- Notification if more than one owner --}}
@if ($ownerCount > 1)
    <div class="flex items-start gap-1.5 rounded-xl bg-primary-50 p-2.5">
        <svg class="h-5 w-5 shrink-0 text-primary-500"><use xlink:href="#icon-information-circle"></use></svg>

        <p class="body text-sm text-primary-500">
            Dit bestand is gekoppeld op {{ $ownerCount }} plaatsen. Als je aanpassingen doet aan dit bestand of dit
            bestand vervangt, wordt dit op alle gekoppelde plaatsen doorgevoerd.

            @if (! $currentOwner)
                <x-chief::link
                    x-on:click="$dispatch('open-dialog', { 'id': 'file-owners-modal-{{ $this->getId() }}' })"
                    class="underline"
                >
                    Bekijk koppelingen
                </x-chief::link>
            @endif
        </p>
    </div>
    {{-- Notification in mediagallery file edit if one and only one owner --}}
@elseif ($ownerCount == 1 && ! $currentOwner)
    <div class="flex items-start gap-1.5 rounded-md bg-primary-50 p-2.5">
        <svg class="h-5 w-5 shrink-0 text-primary-500"><use xlink:href="#icon-information-circle"></use></svg>

        <p class="body text-sm text-primary-500">
            Dit bestand wordt gebruikt op:
            @foreach ($previewFile->owners as $owner)
                <x-chief::link
                    href="{{ $owner['adminUrl'] }}"
                    title="Bekijk"
                    target="_blank"
                    rel="noopener"
                    class="break-all"
                >
                    {{ $owner['label'] }}
                </x-chief::link>
            @endforeach
        </p>
    </div>
    {{-- Notification if no owner --}}
@elseif ($ownerCount == 0)
    <div class="flex items-start gap-1.5 rounded-md bg-primary-50 p-2.5">
        <svg class="h-5 w-5 shrink-0 text-primary-500"><use xlink:href="#icon-information-circle"></use></svg>

        <p class="body text-sm text-primary-500">Dit bestand wordt momenteel nergens gebruikt.</p>
    </div>
@endif
