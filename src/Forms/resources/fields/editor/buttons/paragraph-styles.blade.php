<x-chief-form::editor.button
    x-on:click="$dispatch('open-dialog', { id: 'tiptap-header-paragraph-styles-{{ $locale }}' })"
>
    <x-chief-form::editor.icon.paragraph />
</x-chief-form::editor.button>

<x-chief::dialog.dropdown id="tiptap-header-paragraph-styles-{{ $locale }}" placement="bottom-center">
    <x-chief-form::editor.dropdown.item
        x-on:click="() => {
            editor().chain().focus().setHeading({ level: 1 }).run()
            close();
        }"
    >
        <x-chief-form::editor.icon.heading-one />
        Heading 1
    </x-chief-form::editor.dropdown.item>

    <x-chief-form::editor.dropdown.item
        x-on:click="() => {
            editor().chain().focus().setHeading({ level: 2 }).run()
            close();
        }"
    >
        <x-chief-form::editor.icon.heading-two />
        Heading 2
    </x-chief-form::editor.dropdown.item>

    <x-chief-form::editor.dropdown.item
        x-on:click="() => {
            editor().chain().focus().setHeading({ level: 3 }).run()
            close();
        }"
    >
        <x-chief-form::editor.icon.heading-three />
        Heading 3
    </x-chief-form::editor.dropdown.item>

    <x-chief-form::editor.dropdown.item
        x-on:click="() => {
            editor().chain().focus().setHeading({ level: 4 }).run()
            close();
        }"
    >
        <x-chief-form::editor.icon.heading-four />
        Heading 4
    </x-chief-form::editor.dropdown.item>

    <x-chief-form::editor.dropdown.item
        x-on:click="() => {
            editor().chain().focus().setHeading({ level: 5 }).run()
            close();
        }"
    >
        <x-chief-form::editor.icon.heading-five />
        Heading 5
    </x-chief-form::editor.dropdown.item>

    <x-chief-form::editor.dropdown.item
        x-on:click="() => {
            editor().chain().focus().setHeading({ level: 6 }).run()
            close();
        }"
    >
        <x-chief-form::editor.icon.heading-six />
        Heading 6
    </x-chief-form::editor.dropdown.item>

    <x-chief-form::editor.dropdown.item
        x-on:click="() => {
            editor().chain().focus().setParagraph().run()
            close();
        }"
    >
        <x-chief-form::editor.icon.text />
        Paragraph
    </x-chief-form::editor.dropdown.item>
</x-chief::dialog.dropdown>
