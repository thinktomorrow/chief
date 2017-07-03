@extends('back._layouts.master')

@section('content')

    @role('Admin')
        Welkom op jouw Admin dashboard
    @endrole
    @role('User')
        Welkom op jouw User dashboard
    @endrole
@stop