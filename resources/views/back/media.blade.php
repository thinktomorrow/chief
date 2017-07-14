@extends('back._layouts.master')

@section('page-title')
    Artikels
@stop

@section('topbar-right')

@stop

@section('content')
    <!-- Demo Content: Center Column Text -->
    <div class="ph10">
      <h4> Example Center Content </h4>
      <hr class="alt short">
      <p class="text-muted"> Lorem ipsum dolor sit amet,  is nisi ut aliquip ex ea commodo consectetur adipi sicing elit, sed do eiusmod tempor incididu ut labore et is nisi ut aliquip ex ea commodo dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exetation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    </div>
@stop

@section('sidebar')
  @include('back._partials.mediaslidemenu')
@stop

@push('custom-scripts')
    <script>
      $(document).ready(function(){
        $(document.body).removeClass('sb-r-c');
        $(document.body).addClass('sb-r-o');
      });
    </script>
@endpush
