@extends('chief::back._layouts.master')

@section('page-title','Audit')

@section('header')
    <div class="container-sm">
        @component('chief::back._layouts._partials.header')
            @slot('title', 'Audit')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-link-label type="back">Dashboard</x-link-label>
                </a>
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        @include('chief::admin.audit._rows')
    </div>
@stop
