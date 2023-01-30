@php
    $title = ucfirst($resource->getLabel());
@endphp

<x-chief::page.template :title="$title">
    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="[$resource->getPageBreadCrumb()]" class="max-w-3xl">
            <button form="createForm" type="submit" class="btn btn-primary">Aanmaken</button>
        </x-chief::page.hero>
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

    @include('chief::components.file-component')
    @include('chief::components.filesupload-component')
</x-chief::page.template>


@push('custom-scripts-after-vue')
    @include('chief::layout._partials.editor-script')
@endpush
