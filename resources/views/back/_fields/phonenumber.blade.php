<input type="tel" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" value="{{ old($key, $field->getValue($locale ?? null)) }}">

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
