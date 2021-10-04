@extends('chief::layout.master')

@section('page-title')
    Design system
@stop

@section('content')
    <div class="container">
        <div class="row-start-start gutter-3">
            <div class="w-full">
                <h1 class="text-4xl font-extrabold text-grey-900">Page title</h1>
            </div>

            <div class="w-full lg:w-2/3">
                <x-chief-window title="Your own window title explaining what is it inside of it">
                    <p class="text-grey-700">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </x-chief-window>
            </div>

            <div class="w-full lg:w-1/3">
                <x-chief-window title="Status">
                    <x-slot name="labels">
                        <x-chief-label type="success" size="xs">Online</x-chief-label>
                    </x-slot>

                    <p class="text-grey-700">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    </p>
                </x-chief-window>
            </div>

            <div class="w-full">
                <div class="window window-white window-sm">
                    <div class="row-start-start gutter-1">
                        <div>
                            <span class="btn btn-primary">Primary</span>
                        </div>

                        <div>
                            <span class="btn btn-primary-outline">Primary outline</span>
                        </div>

                        <div>
                            <span class="btn btn-secondary">Secondary</span>
                        </div>

                        <div>
                            <span class="btn btn-secondary-outline">Secondary outline</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full">
                <div class="window window-white window-sm">
                    <div class="row-start-start gutter-3">
                        <div class="flex w-full space-x-1">
                            <div> <x-chief-label size="xs" type="error">Fout</x-chief-label> </div>
                            <div> <x-chief-label size="xs" type="success">Online</x-chief-label> </div>
                            <div> <x-chief-label size="xs" type="info">Gepubliceerd</x-chief-label> </div>
                            <div> <x-chief-label size="xs" type="warning">In draft</x-chief-label> </div>
                        </div>

                        <div class="flex w-full space-x-1">
                            <div> <x-chief-label size="sm" type="error">Fout</x-chief-label> </div>
                            <div> <x-chief-label size="sm" type="success">Online</x-chief-label> </div>
                            <div> <x-chief-label size="sm" type="info">Gepubliceerd</x-chief-label> </div>
                            <div> <x-chief-label size="sm" type="warning">In draft</x-chief-label> </div>
                        </div>

                        <div class="flex w-full space-x-1">
                            <div> <x-chief-label size="md" type="error">Fout</x-chief-label> </div>
                            <div> <x-chief-label size="md" type="success">Online</x-chief-label> </div>
                            <div> <x-chief-label size="md" type="info">Gepubliceerd</x-chief-label> </div>
                            <div> <x-chief-label size="md" type="warning">In draft</x-chief-label> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
