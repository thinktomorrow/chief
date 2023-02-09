@props([
   'required', // if set, show asterisk symbol next to label
   'unset', // if set, show label without form-input-label styling (e.g. radio/checkbox input groups)
])

<label {{ $attributes->class(['select-none', 'form-input-label' => !isset($unset)]) }}>
    {{ $slot }}

    @if (isset($required) && $required)
        <span class="text-orange-500">*</span>
    @endif
</label>
