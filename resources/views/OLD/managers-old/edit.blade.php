@extends('chief::layout.master')

@section('page-title',$manager->details()->title)


@component('chief::layout._partials.header')
    @slot('title', $manager->details()->title)
    @slot('subtitle')
        <div class="inline-block">
            <a class="center-y" href="{{ $manager->route('index') }}">
                <svg width="24" height="24" class="mr-4"><use xlink:href="#arrow-left"/></svg>
                {{-- Terug naar alle {{ $manager->details()->plural }} --}}
            </a>
        </div>
    @endslot

    <div class="inline-group-s flex items-center">

        @if($manager->isAssistedBy('publish'))
            {!! $manager->assistant('publish')->publicationStatusAsLabel() !!}
        @endif

        @if($manager->can('update'))
            <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
        @endif

        @include('chief::back.managers._partials.context-menu')
    </div>

@endcomponent

@section('content')
    <div>
        <form id="updateForm" class="mt-3" method="POST" action="{{ $manager->route('update') }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <input type="hidden" name="_method" value="PUT">

            @include($manager->editView(), array_merge([
                'model' => $manager->existingModel(),
                'fields' => $manager->editFields(),
            ], $manager->editViewData()))

        </form>
    </div>
@stop

@push('custom-scripts-after-vue')
    @include('chief::layout._partials.editor-script', ['imageUploadUrl' => $manager->route('upload')])
@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
