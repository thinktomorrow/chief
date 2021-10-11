<x-chief::page>

    <x-chief::field.show key="title" />

    <x-chief::field key="title" />

    <div class="window window-md window-white">
        <x-chief::field.set type="error">
            <x-chief::field.show key="title" />
            <x-chief::field.show key="content" />
        </x-chief::field.set>
    </div>

    <x-chief::field.window title="Categorieën" tagged="top">
        <x-chief::field.multiple tagged="top" />
        <x-chief-inline-notification type="error">
            OPGELET!!!!
        </x-chief-inline-notification>
    </x-chief::field.window>

    @include('back.shop.catalog.products.window.window');

    <x-slot name="sidebar">
        <x-chief::field.multiple tagged="sidebar" />
    </x-slot>

</x-chief::page>
