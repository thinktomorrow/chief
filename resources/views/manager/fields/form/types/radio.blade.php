<div class="space-y-1">
    @foreach($field->getOptions() as $value => $label)
        <label for="{{ $field->getId($locale ?? null) }}_{{ $value }}" class="with-radio">
            <input
                type="radio"
                name="{{ $key }}"
                value="{{ $value }}"
                id="{{ $field->getId($locale ?? null) }}_{{ $value }}"
                {{ old($key, $field->getSelected() ?? $field->getValue($locale ?? null)) == $value ? 'checked="checked"' : null }}
            >

            <span>{!! $label !!}</span>
        </label>
    @endforeach
</div>
