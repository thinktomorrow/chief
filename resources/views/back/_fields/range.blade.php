<input type="range"
       oninput="{{ $field->getName($locale ?? null) }}OutputId.value = {{ $field->getName($locale ?? null) }}InputId.value"
       name="{{ $field->getName($locale ?? null) }}"
       value="{{ old($key, $field->getValue($locale ?? null)) }}"
       step="{{ $field->getSteps() }}"
       min="{{ $field->getMin() }}"
       max="{{ $field->getMax() }}"
       id="{{ $field->getName($locale ?? null) }}InputId"
       class="input block mb-4"
>
<span class="mr-2">Waarde:</span>
<output name="{{ $field->getName($locale ?? null) }}Output" id="{{ $field->getName($locale ?? null) }}OutputId">
    {{ old($key, $field->getValue($locale ?? null)) }}
</output>
<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
