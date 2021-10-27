<?php

$backgroundClass = 'bg-white';

if(isset($type)) {
    switch($type){
        case 'error':
            $backgroundClass = 'bg-red-50';
            break;
        case 'success':
            $backgroundClass = 'bg-green-50';
            break;
        case 'info':
            $backgroundClass = 'bg-blue-50';
            break;
        case 'warning':
            $backgroundClass = 'bg-orange-50';
            break;
    }
}

?>

{{-- TODO(tijs): can we cleanup these first set classes? a :nth-child solution perhaps? --}}
<div class="-window-x {{ isset($isFirstWindowItem) ? '-mt-8' : null }} {{ isset($isLastWindowItem) ? '-mb-8' : null }}">
    <div class="
        window-x py-8 border-grey-100
        {{ $backgroundClass }}
        {{ isset($isFirstWindowItem) ? 'rounded-t-window' : null }}
        {{ isset($isLastWindowItem) ? 'rounded-b-window' : null }}
        {{ (isset($borderTop) && $borderTop) ? 'border-t' : null }}
        {{ (isset($borderBottom) && $borderBottom) ? 'border-b' : null }}
            ">
        <div class="space-y-6">
            @if(isset($title) || isset($description))
                <div class="space-y-1">
                    @if(isset($title))
                        <h3 class="text-lg font-semibold text-grey-900">{{ ucfirst($title) }}</h3>
                    @endif

                    @if(isset($description))
                        <div class="prose prose-dark prose-editor">
                            <p>{!! $description !!}</p>
                        </div>
                    @endif
                </div>
            @endif

            <div>
                <div class="row-start-start gutter-4">
                    {!! $slot !!}
                </div>
            </div>
        </div>
    </div>
</div>
