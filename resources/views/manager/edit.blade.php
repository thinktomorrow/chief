<x-chief::page>

    <x-slot name="header">
        <x-chief::field.window tagged="pagetitle" />
        <!-- TODO: header comp. -->
    </x-slot>

    <x-chief::field.window untagged />

    <x-chief::fragments.window />

    <x-slot name="sidebar">
        <x-chief::status.window />
        <x-chief::links.window />

        <x-chief::field.window tagged="sidebar" />
    </x-slot>

</x-chief::page>
