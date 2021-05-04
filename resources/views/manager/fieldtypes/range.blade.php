<div class="space-y-2">
    <input
        type="range"
        oninput="{{ $field->getName($locale ?? null) }}OutputId.value = {{ $field->getName($locale ?? null) }}InputId.value"
        name="{{ $field->getName($locale ?? null) }}"
        value="{{ old($key, $field->getValue($locale ?? null)) }}"
        step="{{ $field->getSteps() }}"
        min="{{ $field->getMin() }}"
        max="{{ $field->getMax() }}"
        id="{{ $field->getName($locale ?? null) }}InputId"
    >

    <div class="space-x-1">
        <span class="text-grey-700">Waarde:</span>

        <output
            name="{{ $field->getName($locale ?? null) }}Output"
            id="{{ $field->getName($locale ?? null) }}OutputId"
            class="font-medium text-grey-700"
        >
            {{ old($key, $field->getValue($locale ?? null)) }}
        </output>
    </div>
</div>
