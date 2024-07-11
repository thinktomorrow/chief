<div x-cloak x-show="selection.length > 0" class="flex flex-wrap items-center gap-2">
    <span
        x-text="
            () => {
                if (selection.length === 1) {
                    return selection.length + ' item geselecteerd'
                } else {
                    return selection.length + ' items geselecteerd'
                }
            }
        "
        class="text-sm text-grey-500"
    ></span>

    <button type="button" class="text-sm font-medium text-grey-800 hover:underline hover:underline-offset-2">
        Selecteer alle {{ $total }} items
    </button>

    <button type="button">
        <x-chief-table-new::button size="sm" color="grey">Pas status aan</x-chief-table-new::button>
    </button>

    <button type="button">
        <x-chief-table-new::button size="sm" color="grey">Voeg tags toe</x-chief-table-new::button>
    </button>

    <button type="button">
        <x-chief-table-new::button size="sm" color="grey">Verwijder tags</x-chief-table-new::button>
    </button>

    <button type="button">
        <x-chief-table-new::button size="sm" color="grey">Exporteer</x-chief-table-new::button>
    </button>

    <button type="button">
        <x-chief-table-new::button
            size="sm"
            color="white"
            iconRight='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#000000" fill="none"> <path d="M11.992 12H12.001" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M11.9842 18H11.9932" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M11.9998 6H12.0088" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
        />
    </button>
</div>
