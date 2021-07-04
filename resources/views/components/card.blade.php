<div class="{{ $class ?? '' }}">
    <div class="space-y-6">
        <div class="flex items-start justify-between">
            @if(isset($title) && $title)
                <span class="text-xl font-semibold text-grey-900">{!! $title !!}</span>
            @endif

            @if(isset($editRequestUrl))
                <a data-sidebar-trigger="{{ $type ?? '' }}" href="{{ $editRequestUrl }}" class="flex-shrink-0 link link-primary">
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
</div>
