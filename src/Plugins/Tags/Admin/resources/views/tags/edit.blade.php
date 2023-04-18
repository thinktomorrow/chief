@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar tags', route('chief.tags.index'));
@endphp

<x-chief::page.template title="Tag aanpassen">
    <x-slot name="hero">
        <x-chief::page.hero title="Tag aanpassen" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">
            <a href="{{ route('chief.tags.delete', $model) }}" title="Tag verwijderen" class="btn btn-error-outline">
                <x-chief::icon-label icon="icon-trash">
                    Tag verwijderen
                </x-chief::icon-label>
            </a>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form id="tagsEditForm" action="{{ route('chief.tags.update', $model->id) }}" method="POST" class="card">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>

            <button class="mt-4 btn btn-primary" type="submit">Bewaar aanpassingen</button>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
