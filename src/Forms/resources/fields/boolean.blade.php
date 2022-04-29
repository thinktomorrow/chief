<div class="space-y-1">
    @foreach($getOptions() as $value => $label)
        <div class="flex items-center gap-4">
            <label for="{{ $getElementId($locale ?? null).'_'.$value }}" class="with-toggle">
                <input
                    type="checkbox"
                    id="{{ $getElementId($locale ?? null).'_'.$value }}"
                    name="{{ $getName($locale ?? null) . '[]' }}"
                    value="{{ $value }}"
                    {{ in_array($value, (array) $getActiveValue($locale ?? null)) ? 'checked="checked"' : '' }}
                >

                <span class="toggle-slider"></span>
            </label>

            {{ $label }}
        </div>
    @endforeach
</div>
