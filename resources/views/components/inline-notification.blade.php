@php
    switch ($type ?? null) {
        case 'default':
            $classesForType = 'border-grey-200 bg-grey-100 text-grey-700';
            break;
        case 'error':
            $classesForType = 'border-red-100 bg-red-50 text-red-500';
            break;
        case 'warning':
            $classesForType = 'border-orange-100 bg-orange-50 text-orange-500';
            break;
        case 'info':
            $classesForType = 'border-blue-100 bg-blue-50 text-blue-500';
            break;
        case 'success':
            $classesForType = 'border-green-100 bg-green-50 text-green-500';
            break;
        default:
            $classesForType = 'border-grey-200 bg-grey-100 text-grey-700';
            break;
    }

    switch ($size ?? null) {
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

<div
    {{ $attributes->merge(['class' => $classesForType . ' ' . $classesForSize . ' inline-block rounded-md border']) }}
>
    {{ $slot }}
</div>
