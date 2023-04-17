@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag categorie aanpassen">
    <x-slot name="hero">
        <x-chief::page.hero title="Tag categorie aanpassen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl"></x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="tagGroupsEditForm" action="{{ route('chief.taggroups.update', $model->id) }}" method="POST" class="card">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>

            <button class="btn btn-primary mt-4" type="submit">Bewaar aanpassingen</button>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
