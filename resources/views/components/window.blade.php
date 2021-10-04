{{--
RULES:
- Never add more than 1 icon. If you need more actions, rather use the dropdown component
- The component can exists without title bar, but never without content

OPTIONS:
- Make it possible to pass labels
--}}

<div>
    <div class="bg-white shadow-sm rounded-2xl">
        <div class="p-6">
            <div class="space-y-1 {{ isset($slot) ? 'pb-4 mb-6 border-b border-grey-100' : null }}">
                <div class="flex items-stretch justify-end space-x-6 leading-tight">
                    @isset($title)
                        <div class="w-full row-start-center gutter-1">
                            <h2 class="inline text-xl font-semibold leading-tight tracking-tight text-grey-900">
                                {!! $title !!}
                            </h2>

                            @isset($labels)
                                {!! $labels !!}
                            @endisset
                        </div>
                    @endisset

                    @if(isset($button))
                        <div>
                            {!! $button !!}
                        </div>
                    @elseif(isset($url) || isset($sidebar))
                        <div class="flex-shrink-0">
                            <a data-sidebar-trigger="{{ $sidebar ?? null }}" href="{{ $url ?? null }}" title="Edit">
                                <x-chief-icon-button type="edit" size="18"></x-icon-chief-button>
                            </a>
                        </div>
                    @endisset
                </div>
            </div>

            @isset($slot)
                <div>
                    {!! $slot !!}
                </div>
            @endisset
        </div>
    </div>
</div>
