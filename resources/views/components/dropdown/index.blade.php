@props([
    'trigger' => null, // Clickable element that opens the dropdown
    'reference' => null, // Reference element for the position of the dropdown (if not available, trigger will be used)
])

<div
    x-cloak
    x-show="open"
    x-data="{ open: false }"
    x-dropdown="{ referenceEl: '{{ $reference ?? $trigger }}'}"
    x-init="() => {
        const trigger = document.querySelector('{{ $trigger }}');
        trigger.addEventListener('click', () => { open = true });
    }"
    x-on:click.outside="open = false"
    class="absolute top-0 right-0 z-10 bg-white rounded-md shadow-lg w-max ring-1 ring-black/5 animate-pop-in"
>
    <div class="py-1 [&>*]:w-full [&>*]:text-left flex-col flex">
        {{ $slot }}
    </div>
</div>
