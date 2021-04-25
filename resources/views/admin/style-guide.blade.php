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
                    <x-icon-label type="back">Dashboard</x-icon-label>
                </a>
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container-sm">
        <div class="row">
            <div class="w-full space-y-6">
                <div class="window window-white prose prose-dark">
                    <h1>Heading 1</h1>
                    <h2>Heading 2</h2>
                    <h3>Heading 3</h3>
                    <h4>Heading 4</h4>
                    <h5>Heading 5</h5>
                    <h6>Heading 6</h6>

                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos, commodi dolore ex placeat ipsum sed saepe iste nam ipsam, asperiores dolorum eligendi quaerat veritatis voluptates perferendis, dolor repellendus facere suscipit.</p>
                </div>

                <div class="window window-white prose prose-dark">
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

                <div class="window window-white prose prose-dark">
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

                <div class="window window-white prose prose-dark">
                    <p>Placeholder for forms</p>
                </div>
            </div>
        </div>
    </div>
@stop
