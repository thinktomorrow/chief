<x-chief::page>
    <x-slot name="header">
        <x-chief-form::forms id="pagetitle" />
    </x-slot>

    <x-chief-form::forms position="main" />
    <x-chief::fragments :owner="$model" />
    <x-chief-form::forms position="main-bottom" />

    <x-slot name="aside">
        <x-chief-form::forms position="aside-top" />
        <x-chief::window.status />
        <x-chief::window.links />
        <x-chief-form::forms position="aside" />
    </x-slot>
</x-chief::page>
