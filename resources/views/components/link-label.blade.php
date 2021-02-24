@php
    $position = $position ?? 'prepend';

    switch($type ?? null) {
        case 'edit':
            $icon = '
                <svg class="link-label-icon inline-block transform transition-150" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            '; break;
        case 'back':
            $icon = '
                <svg class="link-label-icon inline-block transform transition-150" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                </svg>
            '; break;
        case 'add':
            $icon = '
                <svg class="link-label-icon inline-block transform transition-150" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            '; break;
        case 'external-link':
            $icon = '
                <svg class="link-label-icon inline-block transform transition-150" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            '; break;
    }
@endphp

<span class="link-label flex items-center space-x-2">
    @if($position == 'prepend')
        {!! $icon !!}
    @endif

    @if($slot != '')
        <span>{{ $slot }}</span>
    @endif

    @if($position == 'append')
        {!! $icon !!}
    @endif
</span>
