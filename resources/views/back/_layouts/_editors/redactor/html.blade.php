<textarea data-editor data-locale="{{ $locale ?? app()->getLocale() }}" class="inset-s" name="{{ $name ?? $key }}" id="{{ $key }}" cols="10" rows="5" v-pre>{{ old($key, $field->getValue($locale ?? null)) }}</textarea>

<error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
