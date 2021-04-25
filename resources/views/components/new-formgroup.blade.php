<div>
    @isset($label)
        <div class="space-x-1 mb-2">
            @isset($id)
                <label for="{{ $id }}" class="font-medium text-grey-900 leading-none cursor-pointer">
                    {{ $label }}
                </label>
            @else
                <span class="font-medium text-grey-900 leading-none">
                    {{ $label }}
                </span>
            @endisset

            @if(isset($isRequired) && $isRequired)
                <span class="label label-info text-sm leading-none">Verplicht</span>
            @endif
        </div>
    @endisset

    @isset($description)
        <div class="prose prose-dark prose-editor mb-4">
            {{ $description }}
        </div>
    @endisset

    <div class="prose prose-dark">
        {{ $slot }}
    </div>

    @isset($name)
        <div class="mt-2">
            @error($name)
                <x-inline-notification type="error">
                    {{ $message }}
                </x-inline-notification>
            @enderror
        </div>
    @endisset
</div>
