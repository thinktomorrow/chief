<button
    type="button"
    x-data="{ showSuccessMessage: false }"
    x-on:click="() => {
        navigator.clipboard.writeText('{{ $slot }}');
        showSuccessMessage = true;
        setTimeout(() => showSuccessMessage = false, 2000);
    }"
>
    <x-chief::link>
        <svg x-show="!showSuccessMessage"><use xlink:href="#icon-link"></use></svg>
        <svg x-show="showSuccessMessage" class="text-green-500 animate-pop-in"><use xlink:href="#icon-check"></use></svg>
    </x-chief::link>
</button>
