<button
    type="button"
    {{ $attributes->merge(['class' => 'rounded-lg p-1 hover:bg-grey-100 *:size-5 *:text-grey-900']) }}
>
    {{ $slot }}
</button>
