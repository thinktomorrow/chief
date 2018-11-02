<input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, $manager->getFieldValue($key)) }}">
<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
