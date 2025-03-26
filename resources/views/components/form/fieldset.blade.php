@props([
    'rule' => null,
])

@if ($slot->isNotEmpty())
    <fieldset
        data-slot="form-group"
        {{
            $attributes->class([
                '[&>[data-slot=control]+[data-slot=error]]:mt-2.5',
                '[&>[data-slot=description]+[data-slot=control]]:mt-3',
                '[&>[data-slot=description]+[data-slot=tabs]]:mt-2.5',
                '[&>[data-slot=label]+[data-slot=control]]:mt-2',
                '[&>[data-slot=label]+[data-slot=description]]:mt-1',
                '[&>[data-slot=label]+[data-slot=tabs]]:mt-1.5',
            ])
        }}
    >
        {{ $slot }}

        @if ($rule)
            <x-chief::form.error :rule="$rule" />
        @endif
    </fieldset>
@endif
