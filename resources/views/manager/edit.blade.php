<x-chief::page>
    <x-slot name="header">
        <x-chief::window.fields tagged="pagetitle" />
    </x-slot>

    <x-chief::window.fields title="Algemeen" untagged />

    <x-chief::window>
        <div class="space-y-4">
            <x-chief::fields key="intro" />
            <x-chief::fields tagged="seo" />
            <x-chief::fields.form key="title" />
        </div>
    </x-chief::window>

    <x-chief::window.fragments />

    <x-slot name="sidebar">
        <x-chief::window.status />
        <x-chief::window.links />
        <x-chief::window.fields tagged="sidebar" />
        <x-chief::window.fields title="SEO" tagged="seo" />
    </x-slot>
</x-chief::page>
