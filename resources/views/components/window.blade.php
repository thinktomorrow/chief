@php
    $url = $url ?? null;
    $sidebar = $sidebar ?? null;
@endphp

<div>
    <div class="bg-white shadow-sm rounded-xl">
        <div class="p-6">
            @if(isset($title) || isset($label) || isset($url) || isset($sidebar))
                <div class="{{ isset($slot) ? 'pb-5 mb-6 border-b border-grey-100' : null }}">
                    <div class="flex items-stretch justify-end space-x-5">
                        <div class="w-full -mt-0.5 space-x-1">
                            @isset($title)
                                <span class="text-lg font-semibold leading-normal tracking-tight text-black">
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
                            <div class="flex-shrink-0">
                                <a
                                    data-sidebar-trigger="{{ $sidebar }}"
                                    href="{{ $url }}"
                                    title="Aanpassen"
                                    class="inline-block p-2 -m-2 rounded-xl bg-primary-50 bg-gradient-to-br from-primary-50 to-primary-100 icon-label link link-primary"
                                >
                                    <x-chief-icon-label type="edit" size="18"></x-chief-icon-label>
                                </a>
                            </div>
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
