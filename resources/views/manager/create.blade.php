@php
    $title = ucfirst($resource->getLabel());
@endphp

<x-chief::page.template :title="$title">
    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="[$resource->getPageBreadCrumb('create')]" class="max-w-3xl" />
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <div class="card">
            <form id="createForm" method="POST" action="@adminRoute('store')" enctype="multipart/form-data" role="form">
                @csrf

                <div class="space-y-6">
                    <x-chief-form::fields not-tagged="edit,not-on-create" />

                    <button type="submit" class="btn btn-primary">Aanmaken</button>
                </div>
            </form>
        </div>
    </x-chief::page.grid>

    @push('custom-scripts')
        @include('chief::templates.page._partials.editor-script')
    @endpush
</x-chief::page.template>
