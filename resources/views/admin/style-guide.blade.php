@extends('chief::layout.master')

@section('page-title')
    Style Guide
@stop

@section('header')
    <div class="container-sm">
        @component('chief::layout._partials.header')
            @slot('title')
                Style Guide
            @endslot

            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" class="link link-primary">
                    <x-chief-icon-label type="back">Dashboard</x-chief-icon-label>
                </a>
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full space-y-6">
                <div class="overflow-hidden space-y-8 prose window window-white window-md prose-dark">
                    <div class="-mx-8 -mt-8">
                        <div class="px-8 pt-8 pb-4 border-b border-grey-100">
                            <span class="window-title">General typography</span>
                        </div>
                    </div>

                    <div>
                        <h1>Heading 1</h1>
                        <h2>Heading 2</h2>
                        <h3>Heading 3</h3>
                        <h4>Heading 4</h4>
                        <h5>Heading 5</h5>
                        <h6>Heading 6</h6>

                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos, commodi dolore ex placeat ipsum sed saepe iste nam ipsam, asperiores dolorum eligendi quaerat veritatis voluptates perferendis, dolor repellendus facere suscipit.</p>
                    </div>
                </div>

                <div class="space-y-8 prose window window-white window-md prose-dark">
                    <div class="-mx-8 -mt-8">
                        <div class="px-8 pt-8 pb-4 border-b border-grey-100">
                            <span class="window-title">Buttons</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex flex-wrap gutter-1">
                            @foreach ([
                                'btn-primary',
                                'btn-primary-outline',
                                'btn-grey',
                                'btn-grey-outline',
                                'btn-info',
                                'btn-info-outline',
                                'btn-success',
                                'btn-success-outline',
                                'btn-warning',
                                'btn-warning-outline',
                                'btn-error',
                                'btn-error-outline',
                            ] as $buttonStyle)
                                <div>
                                    <a
                                        href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                                        title="Style guide button"
                                        target="_blank"
                                        class="btn {{ $buttonStyle }}"
                                    >
                                        btn {{ $buttonStyle }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-8 prose window window-white window-md prose-dark">
                    <div class="-mx-8 -mt-8">
                        <div class="px-8 pt-8 pb-4 border-b border-grey-100">
                            <span class="window-title">Anchor links</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex flex-wrap gutter-1">
                            @foreach ([
                                'link-primary',
                                'link-black',
                                'link-grey',
                                'link-info',
                                'link-success',
                                'link-warning',
                                'link-error',
                            ] as $linkStyle)
                                <div>
                                    <p>A normal anchor link, which can be used anywhere:
                                        <a
                                            href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                                            title="Style guide link"
                                            target="_blank"
                                            class="link {{ $linkStyle }}"
                                        >
                                            link {{ $linkStyle }}
                                        </a>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-8 window window-white window-md">
                    <div class="-mx-8 -mt-8">
                        <div class="px-8 pt-8 pb-4 border-b border-grey-100">
                            <span class="window-title">Forms</span>
                        </div>
                    </div>

                    <div>
                        <div class="row gutter-4">
                            <div class="w-full lg:w-1/2">
                                <x-chief-formgroup label="First name" id="firstname" name="firstname" isRequired>
                                    <input type="text" id="firstname" name="firstname" placeholder="John">
                                </x-chief-formgroup>
                            </div>

                            <div class="w-full lg:w-1/2">
                                <x-chief-formgroup label="Last name" id="lastname" name="lastname" isRequired>
                                    <input type="text" id="lastname" name="lastname" placeholder="Doe">
                                </x-chief-formgroup>
                            </div>

                            <x-chief-formgroup class="w-full">
                                <label for="rememberCheckbox" class="with-checkbox">
                                    <input id="rememberCheckbox" name="remember" type="checkbox" {{ old('remember') ? 'checked=checked' : null  }}>
                                    <span>Houd me ingelogd</span>
                                </label>
                            </x-chief-formgroup>

                            <x-chief-formgroup label="Message" class="w-full">
                                <textarea id="message" name="message" placeholder="Type your message here ..."></textarea>
                            </x-chief-formgroup>

                            <div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
