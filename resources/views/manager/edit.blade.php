<x-chief::page>
    <x-slot name="header">
        <x-chief::page.breadcrumbs />
        <x-chief::field.window tagged="pagetitle" />
    </x-slot>

    <x-chief::field.window title="Gegevens" untagged />

    <x-chief::fragments.window />

    <x-chief::window title="Een window om te testen">
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Perferendis, ex.</p>
    </x-chief::window>

    <x-slot name="sidebar">
        <x-chief::status.window />
        <x-chief::links.window />

        <x-chief::field.window tagged="sidebar" />
        <x-chief::field.window title="Seo" tagged="seo" />
    </x-slot>
</x-chief::page>
