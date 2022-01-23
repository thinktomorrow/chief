<div class="space-y-1">
    @foreach($getOptions() as $value => $label)
        <label for="{{ $getElementId($locale ?? null).'_'.$value }}" class="with-radio">
            <input
                    type="radio"
                    name="{{ $getName($locale ?? null) }}"
                    value="{{ $value }}"
                    id="{{ $getElementId($locale ?? null).'_'.$value }}"
                    {{ in_array($value, (array) $getActiveValue($locale ?? null)) ? 'checked="checked"' : '' }}
            >
            <span>{!! $label !!}</span>
        </label>
    @endforeach
</div>
