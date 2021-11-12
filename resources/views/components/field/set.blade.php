@php
    switch($type ?? null){
        case 'error':
            $typeStyle = 'bg-red-50 bg-gradient-to-br from-red-50 to-red-100';
            break;
        case 'success':
            $typeStyle = 'bg-green-50 bg-gradient-to-br from-green-50 to-green-100';
            break;
        case 'info':
            $typeStyle = 'bg-blue-50 bg-gradient-to-br from-blue-50 to-blue-100';
            break;
        case 'warning':
            $typeStyle = 'bg-orange-50 bg-gradient-to-br from-orange-50 to-orange-100';
            break;
        default:
            $typeStyle = 'bg-white';
    }
@endphp

<div class="-mx-3 p-3 rounded-lg {{ $typeStyle }}">
    <div class="space-y-6">
        @if(isset($title) || isset($description))
            <div class="space-y-1">
                @if(isset($title))
                    <span class="text-lg display-dark display-base">{{ ucfirst($title) }}</span>
                @endif

                @if(isset($description))
                    <div class="prose prose-dark">
                        {!! $description !!}
                    </div>
                @endif
            </div>
        @endif

        <div class="space-y-6">
            {!! $slot !!}
        </div>
    </div>
</div>
