<textarea data-editor class="inset-s" name="{{ $name ?? $key }}" id="{{ $key }}" cols="10" rows="5">{{ old($key, $manager->fieldValue($field, $locale ?? null)) }}</textarea>
<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
