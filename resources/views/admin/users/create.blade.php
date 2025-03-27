@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar admins', route('chief.back.users.index'));
@endphp

<x-chief::page.template title="Nieuwe gebruiker uitnodigen" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Admins', 'url' => route('chief.back.users.index'), 'icon' => 'user'],
                'Nieuwe gebruiker uitnodigen'
            ]"
        >
            <x-slot name="actions">
                <x-chief::button form="createForm" type="submit" variant="blue">
                    <x-chief::icon.sent />
                    <span>Uitnodiging versturen</span>
                </x-chief::button>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief::window>
        <form id="createForm" action="{{ route('chief.back.users.store') }}" method="POST">
            @csrf

            @include('chief::admin.users._form')
        </form>
    </x-chief::window>
</x-chief::page.template>
