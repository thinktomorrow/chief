@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\Breadcrumb('Terug naar overzicht', route('chief.back.roles.index'));
    $title = ucfirst($role->name);
@endphp

<x-chief::template :title="$title">
    <x-slot name="hero">
        <x-chief::template.hero :title="$title" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">
            <button form="editForm" type="submit" class="btn btn-primary">Rol opslaan</button>
        </x-chief::template.hero>
    </x-slot>

    <x-chief::template.grid class="max-w-3xl">
        <form id="editForm" action="{{ route('chief.back.roles.update', $role->id) }}" method="POST" class="card">
            @csrf
            @method('put')

            <div class="space-y-6">
                @include('chief::admin.authorization.roles._form')
            </div>
        </form>
    </x-chief::template.grid>
</x-chief::template>
