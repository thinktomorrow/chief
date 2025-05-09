{{-- Notification if more than one owner --}}
@if ($ownerCount > 1)
    <x-chief::callout data-slot="form-group" size="sm" variant="blue" title="Gekoppeld bestand">
        <x-slot name="icon">
            <x-chief::icon.solid.information-diamond />
        </x-slot>

        <p>
            Dit bestand is gekoppeld op {{ $ownerCount }} plaatsen. Als je aanpassingen doet aan dit bestand of dit
            bestand vervangt, wordt dit op alle gekoppelde plaatsen doorgevoerd.

            @if (! $currentOwner)
                <x-chief::link
                    x-on:click="$dispatch('open-dialog', { 'id': 'file-owners-modal-{{ $this->getId() }}' })"
                    size="sm"
                    class="underline"
                >
                    Bekijk koppelingen
                </x-chief::link>
            @endif
        </p>
    </x-chief::callout>

    {{-- Notification in mediagallery file edit if one and only one owner --}}
@elseif ($ownerCount == 1 && ! $currentOwner)
    <x-chief::callout data-slot="form-group" size="sm" variant="blue" title="Gekoppeld bestand">
        <x-slot name="icon">
            <x-chief::icon.solid.information-diamond />
        </x-slot>

        <p>
            Dit bestand wordt gebruikt op:
            @foreach ($previewFile->owners as $owner)
                <x-chief::link
                    href="{{ $owner['adminUrl'] }}"
                    title="Bekijk"
                    target="_blank"
                    rel="noopener"
                    size="sm"
                    class="break-all underline"
                >
                    {{ $owner['label'] }}
                </x-chief::link>
            @endforeach
        </p>
    </x-chief::callout>

    {{-- Notification if no owner --}}
@elseif ($ownerCount == 0)
    <x-chief::callout data-slot="form-group" size="sm" variant="orange">
        <x-slot name="icon">
            <x-chief::icon.solid.information-diamond />
        </x-slot>

        <p>Dit bestand wordt momenteel nergens gebruikt.</p>
    </x-chief::callout>
@endif
