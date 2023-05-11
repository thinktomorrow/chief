@php
    switch($type ?? null) {
        case 'default':
            $classesForType = 'bg-grey-100 text-grey-700 border-grey-200';
            break;
        case 'error':
            $classesForType = 'bg-red-50 text-red-500 border-red-100';
            break;
        case 'warning':
            $classesForType = 'bg-orange-50 text-orange-500 border-orange-100';
            break;
        case 'info':
            $classesForType = 'bg-blue-50 text-blue-500 border-blue-100';
            break;
        case 'success':
            $classesForType = 'bg-green-50 text-green-500 border-green-100';
            break;
        default:
            $classesForType = 'bg-grey-100 text-grey-700 border-grey-200';
            break;
    }

    switch($size ?? null) {
        case 'small':
            $classesForSize = 'px-2 py-1 text-sm';
            break;
        case 'medium':
            $classesForSize = 'px-4 py-3';
            break;
        case 'large':
            $classesForSize = 'px-6 py-4';
            break;
        default:
            $classesForSize = 'px-2 py-1 text-sm';
            break;
    }
@endphp

<div {{ $attributes->merge(['class' => $classesForType . ' ' . $classesForSize . ' inline-block rounded-md border']) }}>
    {{ $slot }}
</div>
