<div>
    <div class="relative flex items-center justify-end cursor-pointer">
        <select
                {{ $allowMultiple() ? 'multiple' : '' }}
                name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
                id="{{ $getElementId($locale ?? null) }}"
                class="select"
        >
            <option value="">---</option>

            @foreach($getOptions() as $key => $value)
                <option
                        {{ in_array($key, (array) $getActiveValue($locale ?? null)) ? 'selected' : '' }}
                        value="{{ $key }}"
                >{{ $value }}</option>
            @endforeach
        </select>

        <span class="absolute pr-3 pointer-events-none">
            <svg width="16" height="16" class="text-grey-700"><use xlink:href="#icon-chevron-down"></use></svg>
        </span>
    </div>
</div>
