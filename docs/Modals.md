## Dialog

Example of a Livewire modal:

```html
<button type="button" wire:click="toggleDialog()">Open wired dialog</button>

<x-chief::dialog.modal wired> @if($isOpen) ... @endif </x-chief::dialog.modal>
```

Example of a normal modal:

```html
<button type="button" x-data x-on:click="$dispatch('open-dialog', { 'id': 'the-normal-modal' })">
    Open normal dialog
</button>

<x-chief::dialog.modal id="the-normal-modal"> ... </x-chief::dialog.modal>
```
