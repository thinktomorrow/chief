@extends('back._layouts.master')

@section('page-title')
    Artikels
@stop

@section('topbar-right')

@stop

@section('content')
<div class="tray">
  @include('back.media.filter')
  @include('back.media.gallery')
</div>
@section('sidebar')
  @include('back._partials.mediaslidemenu')
  @include('back.media._partials.upload')
@stop

@push('custom-scripts')
    <script>
      $(document).ready(function(){
        $(document.body).removeClass('sb-r-c');
        $(document.body).addClass('sb-r-o');
      });
    </script>
@endpush
