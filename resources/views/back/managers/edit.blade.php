@extends('chief::back._layouts.master')

@section('page-title',$manager->details()->title)


@component('chief::back._layouts._partials.header')
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
            @include('chief::back.managers._partials.context-menu')
        @endif
    </div>

@endcomponent

@section('content')
    <div>

        <!-- needs to be before form to be detected by context-menu. Don't know why :s -->
        @include('chief::back.managers._partials.delete-modal')

        @if($manager->isAssistedBy('archive'))
            @include('chief::back.managers._partials.archive-modal')
        @endif

        <form id="updateForm" class="mt-3" method="POST" action="{{ $manager->route('update') }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <input type="hidden" name="_method" value="PUT">

                @include('chief::back.managers._partials._form', [
                    'fieldArrangement' => $manager->fieldArrangement('edit')
                ])

        </form>
    </div>
@stop

@push('custom-scripts-after-vue')
    @include('chief::back._layouts._partials.editor-script', ['imageUploadUrl' => $manager->route('upload')])
@endpush

@include('chief::back._elements.file-component')
@include('chief::back._elements.slimcropper-component')
@include('chief::back._elements.fileupload-component')
