@extends('chief::layout.master')

@section('page-title')
    Vaste teksten
@endsection

@push('custom-styles')
    <link rel="stylesheet" href="{{ asset('assets/back/css/vendor/redactor.css') }}">
    @include('squanto::_preventDuplicateSubmissions')
@endpush

@section('content')
    {{ $slot }}
@stop

@push('custom-scripts-after-vue')
    <script src="{{ asset('/assets/back/js/vendor/redactor.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(document.querySelectorAll('.redactor-editor').length > 0) {
                $R('.redactor-editor', {
                    buttons: ['html', 'format', 'bold', 'italic', 'sup', 'sub', 'strikethrough', 'lists', 'link']
                });
            }
        });
    </script>
@endpush
