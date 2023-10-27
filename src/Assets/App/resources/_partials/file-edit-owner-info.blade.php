{{-- Notification if more than one owner --}}
@if($ownerCount > 1)
    <div class="flex items-start gap-1.5 p-2.5 rounded-xl bg-primary-50">
        <svg class="w-5 h-5 text-primary-500 shrink-0"><use xlink:href="#icon-information-circle"></use></svg>

        <p class="text-sm body text-primary-500">
            Let op, dit bestand is gekoppeld op {{ $ownerCount }} pagina's.
            Als je aanpassingen doet aan dit bestand, worden deze op alle gekoppelde pagina's doorgevoerd.
        </p>
    </div>
{{-- Notification in mediagallery file edit if one and only one owner --}}
@elseif($ownerCount > 0 && !$currentOwner)
    <div class="flex items-start gap-1.5 p-2.5 rounded-md bg-primary-50">
        <svg class="w-5 h-5 text-primary-500 shrink-0"><use xlink:href="#icon-information-circle"></use></svg>

        <p class="text-sm body text-primary-500">
            Let op, dit bestand wordt gebruikt op één pagina:
            @foreach($previewFile->owners as $owner)
                <a href="{{ $owner['adminUrl'] }}" title="Bekijk" target="_blank" rel="noopener">
                    <x-chief::link underline class="font-medium break-all text-primary-500">{{ $owner['label'] }}</x-chief::link>
                </a>
            @endforeach
            Als je aanpassingen doet aan dit bestand, worden deze op alle gekoppelde pagina's doorgevoerd.
        </p>
    </div>
{{-- Notification if no owner --}}
@elseif($ownerCount == 0)
    <div class="flex items-start gap-1.5 p-2.5 rounded-md bg-primary-50">
        <svg class="w-5 h-5 text-primary-500 shrink-0"><use xlink:href="#icon-information-circle"></use></svg>

        <p class="text-sm body text-primary-500">
            Dit bestand wordt momenteel nergens gebruikt.
        </p>
    </div>
@endif
