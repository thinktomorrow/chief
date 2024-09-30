<x-chief-form::editor.button x-on:click="$dispatch('open-dialog', { id: 'tiptap-header-list-styles-{{ $locale }}' })">
    <x-chief-form::editor.icon.list-bullet />
</x-chief-form::editor.button>

<x-chief::dialog.dropdown id="tiptap-header-list-styles-{{ $locale }}" placement="bottom-center">
    <x-chief-form::editor.dropdown.item
        x-on:click="() => {
            editor().chain().focus().toggleBulletList().run()
            close()
        }"
    >
        <x-chief-form::editor.icon.list-bullet />
        Bullet list
    </x-chief-form::editor.dropdown.item>

    <x-chief-form::editor.dropdown.item
        x-on:click="() => {
            editor().chain().focus().toggleOrderedList().run()
            close()
        }"
    >
        <x-chief-form::editor.icon.list-number />
        Ordered list
    </x-chief-form::editor.dropdown.item>
</x-chief::dialog.dropdown>
