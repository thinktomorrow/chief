<div class="{{ $class ?? '' }}">
    <div class="space-y-6">
        <div class="flex justify-between items-start">
            @if(isset($title) && $title)
                {{-- <h3>{{ $title }}</h3> --}}
                <span class="text-xl font-semibold text-grey-900">{{ $title }}</span>
            @endif

            @if(isset($editRequestUrl))
                <a data-sidebar-{{ $type ?? '' }}-edit href="{{ $editRequestUrl }}" class="flex-shrink-0 link link-primary">
                    <x-icon-label type="edit"></x-icon-label>
                </a>
            @endif
        </div>

        @if($slot)
            <div>
                {{ $slot }}
            </div>
        @endif
    </div>


    {{-- <div class="relative">
        @if(isset($editRequestUrl))
            <a data-sidebar-{{ $type ?? '' }}-edit href="{{ $editRequestUrl }}" class="absolute right-0 top-0 link link-primary">
                <x-icon-label type="edit"></x-icon-label>
            </a>
        @endif

        <div class="space-y-6">
            @if(isset($title) && $title)
                <h3 class="leading-none mr-8">{{ $title }}</h3>
            @endif

            @if($slot)
                <div>
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div> --}}
</div>
