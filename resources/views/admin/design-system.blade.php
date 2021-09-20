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

            <div class="w-full">
                <div class="bg-white rounded-2xl shadow-sm">
                    <div class="px-8 py-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold tracking-tight text-grey-900">Title of a window</h2>

                            <span class="p-2 -m-2 rounded-full link link-primary bg-primary-50 hover:bg-primary-100">
                                <x-icon-label type="edit"></x-icon-label>
                            </span>
                        </div>

                        <p class="mt-2 text-lg font-medium text-grey-500">
                            Sed do eiusmod tempor incididunt ut labore
                        </p>

                        <p class="mt-2 text-grey-500">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                    </div>

                    <div class="border-t border-grey-200 divide-y divide-grey-200">
                        <div class="px-8 py-4">
                            test
                        </div>

                        <div class="px-8 py-4">
                            test
                        </div>

                        <div class="px-8 py-4">
                            test
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-1/3">
                <x-chief-window title="Your own window title explaining what is it inside of it">
                    <p class="text-grey-700">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </x-chief-window>
            </div>

            <div class="w-1/3">
                <x-chief-window title="Your own window title explaining what is it inside of it">
                    <x-slot name="button">
                        <p class="leading-tight">sjdfksf</p>
                    </x-slot>
            
                    <p class="text-grey-700">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </x-chief-window>
            </div>

            <div class="w-1/3">
                <x-chief-window title="Your own window title explaining what is it inside of it">
                    <x-slot name="labels">
                        <span class="inline-block text-xs leading-tight label label-info">Done</span>
                    </x-slot>

                    <p class="text-grey-700">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </x-chief-window>
            </div>

            <div class="w-1/3">
                <div class="relative bg-white rounded-2xl shadow-sm">
                    <div class="p-6">
                        <div class="flex items-stretch justify-between pb-6 mb-6 border-b border-grey-100">
                            <div class="flex items-center mr-4">
                                <h2 class="text-lg font-semibold leading-tight tracking-tight text-grey-900">Status</h2>
                            </div>

                            <div>
                                <span class="flex-shrink-0 p-2 -m-2 rounded-xl link link-primary bg-primary-50 hover:bg-primary-100">
                                    <x-icon-label type="edit" size="20"></x-icon-label>
                                </span>
                            </div>
                        </div>

                        <p class="text-grey-700">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="w-1/3">
                <div class="bg-white rounded-2xl shadow-sm">
                    <div class="px-8 py-6">
                        <p class="text-grey-700">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                    </div>
                </div>
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
        </div>
    </div>
@stop
