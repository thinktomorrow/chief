<input type="number" step="{{ $field->getSteps() }}" min="{{ $field->getMin() }}" max="{{ $field->getMax() }}" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" value="{{ old($key, $manager->fieldValue($field, $locale ?? null)) }}">

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
