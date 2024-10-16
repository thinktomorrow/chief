@props(['wired' => false, 'id' => null])

<div
    x-cloak
    wire:ignore.self
    x-show="isOpen"
    x-data="{
        isOpen: {{ $wired ? '$wire.entangle(\'isOpen\')' : 'false' }},
        open() {
            this.isOpen = true
        },
        close() {
            {{ $wired ? '$wire.close()' : '$data.isOpen = false;' }}
        },
    }"
    x-on:open-dialog.window="
        const firstChild = $el.firstElementChild
        if (! firstChild) {
            throw new Error(
                'Dialog component should be wired or must have a child element with an id attribute.',
            )
        }
        if (firstChild.id === $event.detail.id) {
            open()
            $dispatch('dialog-opened', {
                id: $event.detail.id,
                el: $el,
                trigger: $event.target,
            })
        }
    "
    {{ $attributes->class(['absolute']) }}
>
    {{ $slot }}
</div>
