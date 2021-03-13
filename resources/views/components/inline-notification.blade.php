@php
    switch($type ?? null) {
        case 'error':
            $classesForType = 'bg-red-50 text-red-500';
            break;
        case 'warning':
            $classesForType = 'bg-orange-50 text-orange-500';
            break;
        case 'info':
            $classesForType = 'bg-blue-50 text-blue-500';
            break;
        case 'success':
            $classesForType = 'bg-green-50 text-green-500';
            break;
        default:
            $classesForType = 'bg-blue-50 text-blue-500';
            break;
    }

    switch($size ?? null) {
        case 'small':
            $classesForSize = 'p-4';
            break;
        case 'large':
            $classesForSize = 'p-6';
            break;
        default:
            $classesForSize = 'p-4';
            break;
    }
@endphp

<div class="{{ $classesForType }} {{ $classesForSize }} font-medium rounded-lg">
    {{ $slot }}
</div>
