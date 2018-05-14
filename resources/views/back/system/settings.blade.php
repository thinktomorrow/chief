@extends('back._layouts.master')

@section('content')

    <div class="text-center stack-xl">
        <h1>Settings</h1>
        <div class="inline-group">
            <a href="" class="text-subtle">gebruikers</a>
            <a href="{{ route('back.roles.index') }}">rollen</a>
            <a href="" class="text-subtle">bedrijfsgegevens</a>
            <a href="" class="text-subtle">seo</a>
        </div>

    </div>

@stop
