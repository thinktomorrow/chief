<button
    type="button"
    {{ $attributes->merge(['class' => 'inline-flex items-start gap-1.5 px-2.5 py-1.5 text-sm/5 text-grey-900 *:size-5 hover:bg-grey-100']) }}
>
    {{ $slot }}
</button>
