{{--
    Trigger: Clickable element that opens the dropdown
    Reference: Element used as a reference for the position of the dropdown (if not available, trigger will be used)
--}}

@props([
    'trigger' => null,
    'reference' => null,
])

<div
    x-cloak
    x-show="isOpen"
    x-data="{
        isOpen: false,
        open() {
            this.isOpen = true
            $dispatch('dropdown-opened', { el: $el })
        },
        close() {
            this.isOpen = false
            $dispatch('dropdown-closed')
        },
    }"
    x-dropdown="{ referenceEl: '{{ $reference ?? $trigger }}' }"
    x-init="
        () => {
            const trigger = document.querySelector('{{ $trigger }}')
            trigger.addEventListener('click', () => {
                open()
            })
        }
    "
    x-on:click.outside="close()"
    {{ $attributes->class(['absolute right-0 top-0 z-10 w-max animate-pop-in rounded-md bg-white shadow-lg ring-1 ring-black/5']) }}
>
    <div class="flex flex-col py-1 [&>*]:w-full [&>*]:text-left">
        {{ $slot }}
    </div>
</div>
