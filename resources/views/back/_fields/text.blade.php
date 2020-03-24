<div>
    <textarea class="inset-s" name="{{ $name ?? $key }}" id="{{ $key }}" cols="5" rows="5" style="resize: vertical;" v-pre>{{ old($key, $field->getValue($locale ?? null)) }}</textarea>
{{--    <error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>--}}
</div>

