@extends('chief::back._layouts.master')

@section('custom-styles')
    <link rel="stylesheet" href="{{ asset('/back/redactor/redactor.css') }}">

    <script src="{{ asset('/back/redactor/redactor.js') }}"></script>
    <script>
        // Defer initiation when dom is ready
        document.addEventListener('DOMContentLoaded', function(){
            if(document.querySelectorAll('.redactor-editor').length > 0) {
                $R('.redactor-editor', {
                    paragraphize: false,
                });
            }
        });

    </script>
    @include('squanto::_preventDuplicateSubmissions')
@endsection

@section('page-title')
    Vertalingen
@endsection

@component('chief::back._layouts._partials.header')
    @slot('title')
        Vertalingen
    @endslot
@endcomponent

@section('content')
    {{ $slot }}
@stop
