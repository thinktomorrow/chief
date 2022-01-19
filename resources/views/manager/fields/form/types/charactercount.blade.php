<div class="px-5 pt-3 pb-2 -mt-1 border rounded-b-md border-grey-100 bg-grey-50" style="font-size: 12px;">
    <span class="font-mono text-sm leading-4 text-grey-500">
        <span data-character-count="{{ $getId($locale ?? null) }}" data-character-count-max="{{ $getCharacterCount() }}">0</span>
        / {{ $getCharacterCount() }} karakters
    </span>
</div>
