<div class="space-y-1">
    @foreach($getOptions() as $value => $label)
        <x-chief-form::formgroup.checkbox
            :for="$getElementId($locale ?? null) . '_' . $value"
            :label="$label"
            :show-as-toggle="$optedForToggleDisplay()"
        >
            <input
                type="checkbox"
                name="{{ $getName($locale ?? null).'[]' }}"
                value="{{ $value }}"
                id="{{ $getElementId($locale ?? null) . '_' . $value }}"
                {{ in_array($value, (array) $getActiveValue($locale ?? null)) ? 'checked="checked"' : '' }}
            >
        </x-chief-form::formgroup.checkbox>
    @endforeach
</div>
