@if (isset($hasCharacterCount) && $hasCharacterCount())
    <div class="-mt-1 rounded-b-md border border-grey-100 bg-grey-50 px-5 pb-2 pt-3" style="font-size: 12px">
        <span class="font-mono text-sm leading-4 text-grey-500">
            <span
                data-character-count="{{ $getElementId($locale ?? null) }}"
                data-character-count-max="{{ $getCharacterCount() }}"
            >
                0
            </span>
            / {{ $getCharacterCount() }} karakters
        </span>
    </div>
@endif
