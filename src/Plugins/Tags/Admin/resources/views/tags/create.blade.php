@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag toevoegen">
    <x-slot name="hero">
        <x-chief::page.hero title="Nieuwe tag toevoegen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl"></x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="tagsCreateForm" action="{{ route('chief.tags.store') }}" method="POST" class="card">
            @csrf

            <div class="space-y-4">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>

            <button class="btn btn-primary mt-4" type="submit">Maak tag aan</button>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
