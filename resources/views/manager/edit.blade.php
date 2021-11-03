<x-chief::page>
    <x-slot name="header">
        <x-chief::page.breadcrumbs />
        <x-chief::field.window tagged="pagetitle" />
    </x-slot>

    <x-chief::field.window title="Algemeen" untagged />

    <x-chief::fragments.window />

    <x-slot name="sidebar">
        <x-chief::status.window />
        <x-chief::links.window />

        <x-chief::field.window tagged="sidebar" />
        <x-chief::field.window title="SEO" tagged="seo" />
    </x-slot>
</x-chief::page>
