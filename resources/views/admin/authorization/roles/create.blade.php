@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\Breadcrumb('Terug naar overzicht', route('chief.back.roles.index'));
@endphp

<x-chief::template title="Nieuwe rol toevoegen">
    <x-slot name="hero">
        <x-chief::template.hero title="Nieuwe rol toevoegen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">
            <button form="createForm" type="submit" class="btn btn-primary">Voeg nieuwe rol toe</button>
        </x-chief::template.hero>
    </x-slot>

    <x-chief::template.grid class="max-w-3xl">
        <form id="createForm" action="{{ route('chief.back.roles.store') }}" method="POST" class="card">
            @csrf

            <div class="space-y-6">
                @include('chief::admin.authorization.roles._form')
            </div>
        </form>
    </x-chief::template.grid>
</x-chief::template>
