@props ([
    'multiple' => false,
    'size' => 'md',
])

<div data-slot="control" class="relative flex items-center justify-end">
    <select
        {!! $multiple ? 'multiple' : null !!}
        {{ $attributes->merge(['data-slot' => 'control'])->class([
            'form-input-field appearance-none', 
            'pr-9' => $size === 'md',  
            'form-input-field-sm pr-7' => $size === 'sm']) 
        }}
    >
        {{ $slot }}
    </select>

    <x-chief::icon.chevron-down
        class="body-dark pointer-events-none absolute {{ match ($size) {
            'md' => 'size-4 right-3',
            'sm' => 'size-4 right-2.25',
            default => 'size-4 right-3',
        } }}"
    />
</div>
