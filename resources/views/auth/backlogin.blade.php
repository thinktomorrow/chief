@extends('back._layouts.login')

@section('content')

    <div class="page-header page-header-login">
        <div class="container">
            <div class="row">
                <div class="greenbox-ctn col-sm-6 centered">
                    <div class="greenbox">

                        <h1 class="greenbox-title">LOGIN</h1>
                        @include('back._elements.errors')

                        <form method="POST" action="/back/login" class="form">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control cta" autofocus="autofocus">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control cta">
                            </div>

                            <div class="form-group hidden">
                                <label for="inputRemember">
                                    <input type="checkbox" name="remember" id="inputRemember" value="1" checked="checked"> Remember Me
                                </label>
                            </div>

                            <div class="form-group">
                                <a href="/" class="btn cta">Terug naar de site</a>
                                <button type="submit" class="cta">Login</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


@stop