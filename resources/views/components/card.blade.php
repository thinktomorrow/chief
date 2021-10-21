<div class="{{ $class ?? '' }}">
    <div class="flex items-start justify-between">
        @if(isset($title) && $title)
            <span class="text-lg font-bold leading-none text-grey-900">{!! $title !!}</span>
        @endif

        <div>
            @if(isset($actions) && $actions)
                {!! $actions !!}
            @endif

            @if(isset($editRequestUrl) && $editRequestUrl)
                <a
                    {{ $sidebarTrigger ?? '' }}
                    href="{{ $editRequestUrl }}"
                    class="flex-shrink-0 link link-primary"
                    style="margin-top: -3px"
                >
                    <x-chief-icon-label type="edit"></x-chief-icon-label>
                </a>
            @endif
        </div>

    </div>

    @if(!empty(trim($slot)))
        <hr class="mt-4 mb-6 text-white -window-x text-opacity-0 bg-grey-100" style="height: 1px">

        <div>
            {{ $slot }}
        </div>
    @endif
</div>
