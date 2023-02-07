@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar admins', route('chief.back.users.index'));
@endphp

<x-chief::page.template title="Nieuwe gebruiker uitnodigen">
    <x-slot name="hero">
        <x-chief::page.hero title="Nieuwe gebruiker uitnodigen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">
            <button form="createForm" type="submit" class="btn btn-primary">Uitnodiging versturen</button>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="createForm" action="{{ route('chief.back.users.store') }}" method="POST" class="card">
            @csrf

            <div class="space-y-6">
                @include('chief::admin.users._form')
            </div>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
