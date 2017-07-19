@extends('back._layouts.master')

@section('page-title')
Dashboard
@stop

@section('topbar-right')

@stop

@section('content')
<div class="row">
  <div class="col-md-4">
  <div class="panel panel-tile text-center br-a br-grey">
    <div class="panel-body">
      @role('Admin')
      <img src="{{ asset('assets/back/img/dashboard-chief.png')}}" alt="Dashboard">
      <h1 class="fs30 mt5 mbn">Welkom {{ Auth::user()->firstname }}</h1>
      <h6 class="text-system">Your new name will be "{{ Auth::user()->getAlterEgo() }}"</h6>

      @endrole
      @role('User')
      <img src="{{ asset('assets/back/img/dashboard.png')}}" alt="Dashboard">
      <h1 class="fs30 mt5 mbn">Welkom {{ Auth::user()->firstname }}</h1>
      <h6 class="text-system">Your new name will be "{{ Auth::user()->getAlterEgo() }}"</h6>

      @endrole
    </div>
  </div>
</div>
</div>
@stop