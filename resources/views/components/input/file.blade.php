@props([
    'id' => null,
])

<label
    for="{{ $id }}"
    data-input-file
    class="relative flex items-center justify-center p-4 bg-white border-2 border-dashed rounded-md shadow-sm pointer-events-none border-grey-400"
>
    <span data-input-file-text class="text-center body-base body-dark">
        {{ $slot }}
    </span>

    <input
        data-input-file-input
        type="file"
        {{ $attributes->class('opacity-0 absolute inset-0 pointer-events-auto cursor-pointer w-full') }}
    >
</label>
