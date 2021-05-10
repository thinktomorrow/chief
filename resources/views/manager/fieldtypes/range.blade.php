<div data-toggle-field-target="{{ $field->getId($locale ?? null) }}" class="space-y-2">
    <input
        type="range"
        oninput="{{ $field->getId($locale ?? null) }}OutputId.value = {{ $field->getId($locale ?? null) }}InputId.value"
        name="{{ $field->getName($locale ?? null) }}"
        value="{{ old($key, $field->getValue($locale ?? null)) }}"
        step="{{ $field->getSteps() }}"
        min="{{ $field->getMin() }}"
        max="{{ $field->getMax() }}"
        id="{{ $field->getId($locale ?? null) }}InputId"
    >

    <div class="space-x-1">
        <span class="text-grey-700">Waarde:</span>

        <output
            name="{{ $field->getId($locale ?? null) }}Output"
            id="{{ $field->getId($locale ?? null) }}OutputId"
            class="font-medium text-grey-700"
        >
            {{ old($key, $field->getValue($locale ?? null)) }}
        </output>
    </div>
</div>
