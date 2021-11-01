@php
    $url = $url ?? null;
    $sidebar = $sidebar ?? null;
@endphp

<div>
    <div class="bg-white rounded-2xl shadow-window">
        <div class="p-6">
            @if(isset($title) || isset($label) || isset($url) || isset($sidebar))
                <div class="{{ isset($slot) ? 'mb-6' : null }}">
                    <div class="flex items-stretch justify-end space-x-4">
                        <div class="w-full space-x-1 mt-0.5">
                            @isset($title)
                                <span class="text-lg font-semibold leading-normal text-black">
                                    {!! $title !!}
                                </span>
                            @endisset

                            @isset($labels)
                                <span class="align-bottom with-xs-labels">
                                    {!! $labels !!}
                                </span>
                            @endisset
                        </div>

                        @if($url)
                            <a
                                data-sidebar-trigger="{{ $sidebar }}"
                                href="{{ $url }}"
                                title="Aanpassen"
                                class="flex-shrink-0"
                            >
                                <x-chief-icon-button icon="icon-edit" />
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            @isset($slot)
                <div>
                    {!! $slot !!}
                </div>
            @endisset
        </div>
    </div>
</div>
