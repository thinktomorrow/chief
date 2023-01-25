@extends('chief::layout.master')

@section('page-title','Audit')

@section('header')
    <div class="container max-w-3xl">
        @component('chief::layout._partials.header')
            @slot('title', 'Audit')

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Dashboard</x-chief-icon-label>
                </a>
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container max-w-3xl">
        @include('chief::admin.audit._rows')
    </div>
@stop
