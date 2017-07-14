@extends('back._layouts.master')

@section('page-title')
    TEST UPLOAD
@stop

@section('topbar-right')

@section('content')
    @include('back._partials.mediaslidemenu')

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


