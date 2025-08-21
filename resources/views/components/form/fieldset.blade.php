@props([
    'rule' => null,
])

@if ($slot->isNotEmpty())
    <fieldset
        data-slot="form-group"
        {{
            $attributes->class([
                '[&_[data-slot=control]+[data-slot=error]]:mt-2',
                '[&_[data-slot=control]+[data-slot=hint]]:mt-2',
                '[&_[data-slot=description]+[data-slot=control]]:mt-3',
                '[&_[data-slot=description]+[data-slot=tabs]]:mt-2.5',
                '[&_[data-slot=label]+[data-slot=control]]:mt-2',
                '[&_[data-slot=label]+[data-slot=description]]:mt-1',
                '[&_[data-slot=label]+[data-slot=tabs]]:mt-1.5',
                '[&_[data-slot=hint]+[data-slot=hint]]:mt-1',
                '[&_[data-slot=error]+[data-slot=error]]:mt-1',
                // TODO: Better way to remove bottom border rounding for fields followed by a character count element
                '[&_[data-slot=control]:has(+[data-slot=charactercount])]:rounded-b-none',
                '[&_[data-slot=control]:has(+[data-slot=charactercount])_.form-input-field]:rounded-b-none',
                '[&_[data-slot=control]:has(+[data-slot=charactercount])_.redactor-box]:rounded-b-none!',
            ])
        }}
    >
        {{ $slot }}

        @if ($rule)
            <x-chief::form.error :rule="$rule" />
        @endif
    </fieldset>
@endif
