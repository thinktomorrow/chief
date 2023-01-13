@extends('chief::layout.solo')

@section('title')
    Log in
@endsection

@section('content')
    <div class="container">
        <div class="absolute top-0 left-0 p-6">
            <svg class="w-24 text-grey-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 229.1 141.7">
                <g fill="currentColor"><path d="M51.1 111.7c2.4-3.4 4.1-7.4 5.1-12.1 1-4.7 1.1-9.6.5-14.8-.2-1.4-.6-2.3-1.2-2.6-.6-.3-1.3-.3-2.1 0-.8.4-1.6 1-2.4 1.8-.8.8-1.6 1.8-2.2 2.8-.7 1-1.1 2-1.5 2.9-.3.9-.4 1.7-.1 2.2 1 2.3 1.4 4.9 1.1 8-.3 3.1-1.1 6-2.6 8.7-1.5 2.7-3.5 4.9-6.3 6.6-2.7 1.7-6 2.3-9.9 1.7-2.7-.4-5.4-1.7-7.9-3.7s-4.5-4.8-6.1-8.2c-1.6-3.5-2.6-7.5-3.1-12.3-.5-4.7 0-9.9 1.5-15.6.3-1.1.6-2.2.9-3.4.3-1.1.6-2.2.9-3.2 2.4 1.8 5.1 3.2 7.9 4.2 2.8 1 5.8 1.6 9 1.9 5.7.5 10.7-.1 15.3-1.9 4.5-1.8 8.3-4.3 11.4-7.4 3.1-3.1 5.4-6.6 7-10.5 1.6-3.9 2.2-7.7 2.1-11.3-.2-3.7-1.3-6.9-3.4-9.7-2.1-2.8-5.2-4.7-9.4-5.7-3.7-.8-7.5-.9-11.4-.2-3.8.6-7.6 2-11.3 4.1-3.7 2.1-7.2 4.8-10.5 8.3-3.3 3.5-6.3 7.6-8.8 12.4-.4-3-.6-6.5-.5-10.7.1-1.3-.5-2.6-1.9-4-1.3-1.4-2.7-2.4-4.3-3.1-1.6-.8-2.9-.9-4.2-.5-1.2.4-1.8 1.7-1.6 4 .7 9.8 3.5 17.8 8.3 23.9-.6 1.4-1.1 2.8-1.5 4.3-.4 1.4-.8 3-1.1 4.5-1.8 7.7-2.2 14.5-1.4 20.4.8 5.9 2.4 10.9 4.7 14.9 2.3 4 5.1 7.2 8.4 9.4 3.3 2.2 6.5 3.4 9.8 3.7 5.1.5 9.6-.2 13.4-2 3.9-1.8 7-4.4 9.4-7.8zm-25.6-59.3c2.9-3.9 5.9-7.2 9-9.9 3.1-2.7 6.3-4.6 9.4-5.8 3.1-1.2 6-1.3 8.5-.5 2.1.8 3.6 2.3 4.6 4.5.9 2.2 1.3 4.8 1.1 7.6-.2 2.9-1 5.8-2.2 8.8-1.3 3-3 5.8-5.1 8.2-2.2 2.4-4.8 4.3-7.8 5.7-3 1.4-6.4 1.8-10.1 1.2-3.5-.5-6.5-1.4-8.8-2.7-2.4-1.3-4.3-2.8-5.9-4.6 1.9-4.3 4.4-8.5 7.3-12.5zM147 97.5c-2.3.6-101.5 26-103.3 26.5-1.8.5-3 .8-3.7 1-1.4.4-2.6 1.4-3.6 2.8-1 1.5-1.6 2.9-1.7 4.4-.1 1.4.5 2.6 1.8 3.4 1.3.8 4.7 2.5 7.9 1.6 1-.2 2.3-.3 4.3-1l105.6-31.6s.5-8.8 1-9.3c.3-.4-5.9 1.6-8.3 2.2zM202.5 79.7c-.5-1.3-1.3-2.6-2.6-3.3-1.3-.7-2.8-1.4-4.5-1.7-1.7-.3-3.6-.6-5.5-.5-2 .1-13.1.1-15.1.3h-.1c-1.3-.1-2.2-.6-2.8-1.6-.6-1-.7-2.7-.2-5l1.3-3.7c4-3.5 7.2-7.2 9.6-11.2 2.4-4 4.1-8 5.1-12s1.4-7.8 1.2-11.4c-.2-3.6-.9-6.8-2.1-9.5-.9-2-1.8-3.6-2.8-5-1-1.4-2-2.3-3.1-2.9-1-.5-2-.7-2.9-.4-.9.3-1.7 1.1-2.3 2.4-.3.5-.8 2-1.5 4.4-.7 2.4-1.5 5.4-2.5 9.1-1 3.7-2 7.9-3.2 12.5-1.2 4.7-2.3 9.5-3.5 14.5-1.2 4.9-2.3 10-3.3 15-.6 2.3-1.5 4.5-2.6 6.5-1.5 2.7-3.2 5-5.1 6.9-1.9 1.9-4 3.4-6.2 4.5-2.2 1-4.3 1.5-6.3 1.3-1.9-.2-3.4-1-4.6-2.3-1.2-1.3-2.2-2.9-2.9-4.7 3.4 1.8 6.6 2.3 9.4 1.7 2.8-.6 5.2-2 7.1-4.2 1.9-2.1 3.4-4.7 4.4-7.8 1-3.1 1.4-6.2 1.3-9.4-.1-2-.4-3.8-1-5.5-.5-1.7-1.3-3.2-2.3-4.5-1-1.3-2.3-2.2-3.9-2.9-1.6-.6-3.4-.8-5.5-.6-3.7.5-6.9 1.9-9.4 4.1-2.5 2.2-4.5 4.9-5.9 8.1-1.4 3.2-2.2 6.6-2.4 10.2-.1 2.1 0 4.2.3 6.2-.3 1-.6 2-1 3-.8 2.4-1.7 4.6-2.7 6.6-.9 2-2 3.6-3 4.8-1.1 1.2-2.2 1.7-3.3 1.5-.7-.1-1.1-.9-1.2-2.3-.1-1.4 0-3.2.3-5.3.3-2.1.8-4.4 1.4-6.9.6-2.5 1.2-5 1.8-7.3.6-2.4 1.2-4.5 1.7-6.5.5-1.9.9-3.4 1.1-4.3.3-1.4 0-2.8-.7-4.1-.8-1.3-1.7-2.2-2.8-2.8-1.1-.5-2.3-.5-3.4.2-1.2.7-2.1 2.4-2.8 5.1-.4 1.7-.9 3.8-1.4 6.2-.6 2.5-1.1 5.2-1.5 8-.4 2.4-.7 4.7-.9 7.1-.3 1-.7 2-1 3.1-.8 2.5-1.7 4.7-2.6 6.7-.9 2-2 3.6-3 4.8-1.1 1.2-2.2 1.7-3.3 1.5-.8-.2-1.1-1.1-1.1-2.8.1-1.7.4-3.7 1-6.2.6-2.4 1.2-5 2-7.8s1.5-5.4 2.1-7.9c.6-2.6.8-5.1.5-7.4-.3-2.3-.9-4.2-1.9-5.6-1-1.4-2.3-2.3-3.9-2.5-1.6-.2-3.4.4-5.4 1.8-2 1.5-4.2 3.9-6.5 7.4-2.3 3.5-4.7 8.2-7.2 14.2 1.1-4.2 2.2-8.6 3.3-13.3 1.1-4.6 2.1-8.9 3.1-13 1-4 1.9-7.5 2.5-10.6.7-3 1.2-5.1 1.4-6.2.3-1.3.1-2.8-.7-4.4-.7-1.6-1.7-2.7-2.9-3.4-1.2-.7-2.4-.7-3.7.1-1.3.8-2.3 2.8-3 6-.2 1.2-.7 3.5-1.2 7.1-.6 3.5-1.2 7.6-1.9 12.3-.7 4.7-1.5 9.6-2.3 14.7-.8 5.2-1.6 10-2.3 14.4-.7 4.4-1.3 8.2-1.7 11.3-.4 3.1-.7 4.9-.8 5.4-.3 1.9-.3 3.3.1 4.2.3.9.7 1.4 1.3 1.4.5 0 1.1-.4 1.8-1.3.6-.9 1.2-2.2 1.8-4 0-.2.2-.9.6-2.2.3-1.3.8-2.7 1.3-4.3.5-1.6 1.1-3.3 1.6-5 .6-1.7 1.1-3.1 1.6-4.1 2.5-5.4 4.8-9.5 7-12.4 2.2-2.9 4-4.8 5.4-5.7 1.5-.9 2.5-.9 3.2 0 .7.9.7 2.5.1 4.7-1.3 4.3-2.2 8.2-2.8 11.7-.6 3.5-.7 6.5-.4 9 .3 2.5 1 4.5 2.1 5.9 1.1 1.4 2.6 2.2 4.5 2.3 2.1.2 4-.6 5.6-2.2 1.6-1.7 3.1-3.7 4.3-6.2.5-1 1-2.1 1.4-3.1 0 .2 0 .3.1.5.4 2.3 1.2 4.2 2.3 5.6 1.1 1.5 2.8 2.3 5.1 2.4 2 .1 3.8-.8 5.4-2.6 1.6-1.8 3.1-4.1 4.3-6.7.9-1.9 1.7-3.9 2.5-5.9 1 2.6 2.4 4.8 4.2 6.7 2.1 2.2 4.8 3.4 8 3.7 3.3.3 6.4-.2 9.3-1.5 2.9-1.3 5.4-3.2 7.7-5.6 1-1.1 1.9-2.2 2.7-3.4l-.4 2.4c-.8 4.6-1.4 8.8-1.9 12.5-.5 3.8-.6 6.8-.5 9.1.1 1.2.4 2.1.9 2.8.5.6 1.2 1 1.8 1.1.7.1 1.3-.1 1.9-.6.6-.5.9-1.2 1-2.2.3-4.1 1-8.5 2-13.1 1.1-4.6 2.6-9.9 4.5-15.9.7.8 1.5 1.4 2.5 1.8 1 .4 2.2.5 3.7.4.7-.1 1.3-.2 2-.4 1.9-.4 3.9-.7 6-1.1 2.5-.4 4.8-.6 7-.7 2.1-.1 4 .1 5.6.4 1.6.4 2.5 1.1 2.9 2.2.1.6-.2 1.1-1 1.7-.8.6-2 1.2-3.5 1.9-1.5.6-3.2 1.3-5.2 1.9-1.9.6-3.9 1.2-5.9 1.8l-5.9 1.7c-1.9.5-3.6 1-5 1.4-.7.2-2 .5-3.7 1l-2.7.6c-.7 2.9-1 6-1.3 9.1l22.6-6.8c3.2-1.2 6.2-2.6 8.8-4.2 2.7-1.6 4.8-3.3 6.3-5.1 1.3-1.8 1.6-3.8.7-5.9zm-25.4-27.9c.5-1.6 1.1-3.2 1.7-5 .6-1.8 1.2-3.5 1.7-5.3.6-1.7 1.1-3.3 1.6-4.8.5-1.4.9-2.6 1.2-3.6l.5-1.6c.3 1.1.4 2.5.3 4.3-.2 1.8-.6 3.7-1.2 5.9-.7 2.1-1.6 4.4-2.7 6.8-1.2 2.4-2.6 4.8-4.2 7.2.1-1 .6-2.3 1.1-3.9zm-42.9 16c1-2.3 2.2-4.2 3.8-5.7 1.6-1.5 3.3-2.4 5.3-2.7 2-.3 3.9.2 5.7 1.6 1.2 4.7 1.3 8.5.2 11.4-1.1 2.9-2.6 4.8-4.6 6-2 1.1-4.2 1.4-6.5.8-2.3-.6-4.1-1.9-5.3-4.1 0-2.5.4-4.9 1.4-7.3z"></path></g>
                <g fill="currentColor"><path d="M201.9 66.6c.2-2 .3-3.7.8-6.4v-.1c1.1-.1 2.5-.5 4-1.4.5-.3.7-.8.6-1.4 0-.2-.1-.4-.3-.6 2-.6 4-1.5 4.6-2.2.4-.4.5-1.1.1-1.6 1.7-1 2.8-2.1 3.5-2.9.3-.3.4-.7.3-1.1-.1-.4-.4-.8-.7-.9l-.6-.3c1.2-.4 2.3-.9 3.3-1.5.2-.1.3-.3.5-.5 1.5-2.6 2.9-5.4 4.1-8.3 1.7-4.1 2.8-7.5 3.8-12.3.1-.5-.1-1-.5-1.3-.3-.2-.7-.3-1-.3.5-.3 1.1-.6 1.7-1 .3-.2.5-.5.5-.8 1.9-10.4 2.3-19.8 2.3-19.9 0-.5-.2-.9-.7-1.2-.4-.2-.9-.2-1.4 0-.6.4-15.8 10-23.8 22-.2.3-.2.6-.2.9.2 1.9.8 3.6 1.5 5.3-1-.3-2-.9-2.4-1.2-.3-.2-.7-.3-1.1-.2-.4.1-.7.4-.8.8-1.2 2.9-2.2 5.8-3 8.7-.1.2-.1.4 0 .6.3 1.2.7 2.4 1.2 3.5l-.6-.2c-.4-.1-.8-.1-1.2.1-.4.2-.6.6-.6 1-.1 1-.1 2.6.4 4.5-.6.1-1 .6-1.1 1.2-.1 1.3 1.1 3.7 1.5 4.4l.2.4c-.2 0-.4.1-.6.2-.5.3-.7.9-.6 1.4.2.9.5 1.7.9 2.4.5.9 1.1 1.7 1.7 2.2-.5 1.5-1.7 6-1.9 7.4-.1 1.5 1 2.7 2.4 2.9 1.8.2 3-.9 3.2-2.3zm-2.2-29.4c.6-2.2 1.4-4.5 2.2-6.7 1.3.6 3.3 1.4 5.3 1 .5-.1.8-.4 1-.9.2-.5.1-1-.3-1.3 0 0-.5-.6-1.1-1.7-.6-1.2-1.1-2.6-1.3-4 6-8.7 16.1-16.3 20.6-19.4-.2 3.4-.8 9.6-2 16.4-2.8 1.9-5.3 2-5.3 2-.6 0-1 .4-1.2.9-.2.5 0 1.1.5 1.4 1.1.8 3.1 1.4 4.8 1.4-.8 3.7-1.8 6.6-3.2 10-1.1 2.6-2.4 5.2-3.8 7.7-1.8 1-3.8 1.6-6 1.9-.6.1-1.1.5-1.2 1.1-.1.6.2 1.2.7 1.4l2.4 1.1c-1.7.3-4.9.6-6.9.8l1.2-3.9c1.6-5 3.6-9.9 5.1-13.5 1.5-3.6 2.6-6 2.6-6s-5.6 9-10.1 18.6c-.6 1.2-1.2 2.5-1.7 3.8-2-2.5-3.1-4.2-3.5-5.1v-.2l2.5 1c.6.2 1.2 0 1.5-.5.4-.5.3-1.1 0-1.6-1.3-1.8-2.2-3.7-2.8-5.7zm0 0"></path></g>
            </svg>
        </div>

        <div class="relative min-h-screen row-center-center">
            <div class="space-y-6 w-128">
                <h1 class="text-center h1 display-dark">Welkom terug, Chief!</h1>

                <div class="card">
                    <form id="valid" role="form" method="POST" action="{{ route('chief.back.login.store') }}">
                        {{ csrf_field() }}

                        <div class="space-y-6">
                            {{-- TODO: field errors are handled but still need to show error if login credentials are incorrect --}}
                            @if($errors && count($errors) > 0)
                                <x-chief-inline-notification type="error" size="large">
                                        @foreach ($errors->all() as $_error)
                                            <p>{{ $_error }}</p>
                                        @endforeach
                                </x-chief-inline-notification>
                            @endif

                            <x-chief-form::formgroup id="email" label="E-mail" required>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="jouwemail@example.com" autofocus>
                                <x-chief-form::formgroup.error error-ids="email"></x-chief-form::formgroup.error>
                            </x-chief-form::formgroup>

                            <x-chief-form::formgroup id="password" label="Wachtwoord" required>
                                <input id="password" name="password" type="password">
                                <x-chief-form::formgroup.error error-ids="password"></x-chief-form::formgroup.error>
                            </x-chief-form::formgroup>

                            <x-chief-form::formgroup id="remember">
                                <label for="rememberCheckbox" class="with-checkbox">
                                    <input
                                            id="rememberCheckbox"
                                            name="remember"
                                            type="checkbox"
                                            {{ old('remember') ? 'checked=checked' : null  }}
                                    >

                                    <span>Hou me ingelogd</span>
                                </label>
                            </x-chief-form::formgroup>

                            <div class="space-x-2">
                                <button type="submit" form="valid" class="btn btn-primary">Log in</button>

                                <a
                                    href="{{ route('chief.back.password.request') }}"
                                    title="Wachtwoord vergeten"
                                    class="btn btn-primary-outline"
                                >
                                    Wachtwoord vergeten?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
