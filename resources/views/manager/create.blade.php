@php
    $title = ucfirst($resource->getLabel());
@endphp

<x-chief::template :title="$title">
    <x-slot name="hero">
        <x-chief::template.hero :title="$title" :breadcrumbs="[$resource->getPageBreadCrumb()]" class="max-w-3xl">
            <button form="createForm" type="submit" class="btn btn-primary">Aanmaken</button>
        </x-chief::template.hero>
    </x-slot>

    <x-chief::template.grid class="max-w-3xl">
        <div class="card">
            <form id="createForm" method="POST" action="@adminRoute('store')" enctype="multipart/form-data" role="form">
                @csrf

                <div class="space-y-6">
                    <x-chief-form::fields not-tagged="edit,not-on-create" />

                    <button type="submit" class="btn btn-primary">Aanmaken</button>
                </div>
            </form>
        </div>
    </x-chief::template.grid>
</x-chief::template>

@include('chief::components.file-component')
@include('chief::components.filesupload-component')

@push('custom-scripts-after-vue')
    @include('chief::layout._partials.editor-script')
@endpush
