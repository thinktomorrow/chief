@extends('chief::back._layouts.master')

@section('page-title')
    {{ $model->adminLabel('title') }}
@endsection

@component('chief::back._layouts._partials.header')
    @slot('title')
        {{ $model->adminLabel('title') }}
    @endslot

    <div class="inline-group-s flex items-center">

        {!! $model->adminLabel('online_status') !!}

        @adminCan('update')
        <button data-submit-form="updateForm{{ $model->modelReference()->get() }}" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
        @endAdminCan

        @include('chief::back.managers._index._options')
    </div>

@endcomponent

@section('content')

    <div>

        <form id="updateForm{{ $model->modelReference()->get() }}" class="mt-3" method="POST" action="@adminRoute('fragment-update', $model)" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">

            @foreach($fields as $field)
                @formgroup
                @slot('label',$field->getLabel())
                @slot('description',$field->getDescription())
                @slot('isRequired', $field->required())
                {!! $field->render(get_defined_vars()) !!}
                @endformgroup
            @endforeach

            <div class="stack text-right">
                <button data-submit-form="updateForm{{ $model->modelReference()->get() }}" type="submit" form="updateForm{{ $model->modelReference()->get() }}" class="btn btn-primary">Wijzigingen opslaan</button>
            </div>

        </form>
    </div>

@stop

@push('custom-scripts-after-vue')

    @adminCan('asyncRedactorFileUpload', $model)
    @include('chief::back._layouts._partials.editor-script', ['imageUploadUrl' => $manager->route('asyncRedactorFileUpload', $model)])
    @endAdminCan

@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
