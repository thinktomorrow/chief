@props(['wired' => false])

<div
    wire:ignore.self
    x-cloak
    x-data="{
        isOpen: {{ $wired ? '$wire.entangle(\'isOpen\')' : 'false' }},
        open() {
            this.isOpen = true
        },
        close() {
            {{ $wired ? '$wire.close()' : '$data.isOpen = false;' }}
        },
    }"
    x-show="$data.isOpen"
    x-on:open-dialog.window="
        const firstChild = $el.firstElementChild
        if (! firstChild) {
            throw new Error(
                'Dialog component should be wired or must have a child element with an id attribute.',
            )
        }
        if (firstChild.id === $event.detail.id) {
            $data.open()
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
