@extends('back._layouts.master')

@section('content')

    @role('Admin')
      <img src="{{ asset('assets/back/img/dashboard.png')}}" alt="Dashboard">
        <h1>Welkom op jouw Admin dashboard</h1>
    @endrole

    @role('User')
        Welkom op jouw User dashboard
    @endrole
@stop