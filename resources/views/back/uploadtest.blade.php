@extends('back._layouts.master')

@section('page-title')
    TEST UPLOAD
@stop

@section('content')
    @include('back.media.show')

    <div>{{ $article->asset()->first()->getFilename() }}</div>
    <div><img src="{{ $article->asset()->first()->getPath() }}" alt=""></div>

    <a data-select-media href="/admin/media-modal">Selecteer uit bibliotheek</a>
@stop

@push('custom-scripts')

    <script>
        $(document).ready(function(){

            $('[data-select-media]').magnificPopup({
                type: 'ajax'
            });

        });
    </script>

@endpush


