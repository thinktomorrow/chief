<x-chief::page>
    <x-slot name="header">
        <x-chief::page.breadcrumbs />
        <x-chief::field.window tagged="pagetitle" />
    </x-slot>

    {{-- <x-chief::window title="Een window om te testen">
        <div class="prose prose-dark">
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Perferendis, ex.</p>
        </div>
    </x-chief::window> --}}

    <x-chief::field.window title="Algemeen" untagged />

    <x-chief::fragments.window />

    <x-slot name="sidebar">
        <x-chief::status.window />
        <x-chief::links.window />

        <x-chief::field.window tagged="sidebar" />
        <x-chief::field.window title="Seo" tagged="seo" />
    </x-slot>
</x-chief::page>
