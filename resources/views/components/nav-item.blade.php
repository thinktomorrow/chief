@php
    $collapsible = $attributes->has('collapsible');
    $open = $attributes->has('open');
    $dropdownIdentifier = uniqid();
@endphp

<div class="relative">
    <div data-toggle-dropdown="{{ $dropdownIdentifier }}" class="rounded-lg cursor-pointer hover:bg-primary-50">
        <div class="flex justify-between">
            <div class="flex space-x-1">
                @isset($icon)
                    @isset($url)
                        <a 
                            href="{{ $url }}"
                            title="{!! $label !!}"
                            class="flex-shrink-0 p-2 pop children:w-6 children:h-6 children:text-grey-700"
                        >
                            {!! $icon !!}
                        </a>
                    @else
                        <div class="flex-shrink-0 p-2 pop children:w-6 children:h-6 children:text-grey-700">
                            {!! $icon !!}
                        </div>
                    @endisset
                @endisset

                @isset($url)
                    <a 
                        data-class-on-collapse="{{ $collapsible ? 'hidden' : null }}" 
                        href="{{ $url }}"
                        title="{!! $label !!}"
                        class="px-3 py-2 link link-black pop"
                    >
                        {!! $label !!}
                    </a>
                @else
                    <span 
                        data-class-on-collapse="{{ $collapsible ? 'hidden' : null }}" 
                        class="px-3 py-2 link link-black pop"
                    >
                        {!! $label !!}
                    </span>
                @endisset
            </div>

            @if(!$slot->isEmpty())
                <div data-class-on-collapse="{{ $collapsible ? 'hidden' : null }}" class="flex-shrink-0 p-2">
                    <span class="inline-flex items-center justify-center w-6 h-6">
                        <svg width="18" height="18" class="text-grey-700"><use xlink:href="#icon-chevron-down"></use></svg>
                    </span>
                </div>
            @endif
        </div>
    </div>
    
    @if(!$slot->isEmpty())
        <div 
            data-dropdown="{{ $dropdownIdentifier }}"
            data-class-on-collapse="absolute left-6 top-0 bg-white rounded-lg shadow-window p-2 min-w-48" 
            class="ml-11 {{ $open ? '' : 'hidden' }}"
        >
            {!! $slot !!}
        </div>
    @endif
</div>
