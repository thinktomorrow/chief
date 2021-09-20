{{-- 
RULES:
- Never add more than 1 icon. If you need more actions, rather use the dropdown component
- The component can exists without title bar, but never without content

OPTIONS:
- Make it possible to pass labels
--}}

<div>
    <div class="bg-white rounded-2xl shadow-sm">
        <div class="p-6">
            <div class="flex items-stretch space-x-6 justify-end leading-tight {{ isset($slot) ? 'pb-6 mb-6 border-b border-grey-100' : null }}">
                @isset($title)
                    <div class="w-full space-x-1">
                        <h2 class="text-xl font-semibold leading-tight tracking-tight text-grey-900 inline -mt-0.5">
                            {!! $title !!}
                        </h2>

                        @isset($labels)
                            {!! $labels !!}
                        @endisset
                    </div>
                @endisset

                @isset($button)
                    <div>
                        {!! $button !!}
                    </div>
                @else
                    <div>
                        <a 
                            data-sidebar-trigger="{{ $sidebar ?? null }}"
                            href="{{ $url ?? '#' }}"
                            title="Edit"
                            class="flex-shrink-0 p-2 -m-2 rounded-xl link link-primary bg-grey-100 hover:bg-primary-100"
                        >
                            <x-icon-label type="edit" size="20"></x-icon-label>
                        </a>
                    </div>
                @endisset
            </div>

            @isset($slot)
                <div>
                    {!! $slot !!}
                </div>
            @endisset
        </div>
    </div>
</div>
