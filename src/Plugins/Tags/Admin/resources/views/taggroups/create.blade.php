@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag categorie toevoegen">
    <x-slot name="hero">
        <x-chief::page.hero title="Nieuwe tag categorie toevoegen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl"></x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="tagGroupsCreateForm" action="{{ route('chief.taggroups.store') }}" method="POST" class="card">
            @csrf

            <div class="space-y-4">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>

            <button class="btn btn-primary mt-4" type="submit">Maak categorie aan</button>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
