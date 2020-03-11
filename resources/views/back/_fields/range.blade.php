<input type="range" oninput="{{ $name ?? $key }}OutputId.value = {{ $name ?? $key }}InputId.value" step="{{ $field->getSteps() }}" min="{{ $field->getMin() }}" max="{{ $field->getMax() }}" name="{{ $name ?? $key }}InputName" id="{{ $name ?? $key }}InputId" class="input block mb-4" value="{{ old($key, $manager->fieldValue($field, $locale ?? null)) }}">
<span class="mr-2">Waarde:</span><output name="{{ $name ?? $key }}OutputName" id="{{ $name ?? $key }}OutputId"></output>
<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
