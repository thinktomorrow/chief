<div
    {!! $attributes->has('data-conditional') ? 'data-conditional="' . $attributes->get('data-conditional') . '"' : null !!}
    {!! $attributes->has('data-conditional-trigger-type') ? 'data-conditional-trigger-type="' . $attributes->get('data-conditional-trigger-type') . '"' : null !!}
    {!! $attributes->get('data-conditional-data') ? 'data-conditional-data="' . $attributes->get('data-conditional-data') . '"' : null !!}
    class="{{ $attributes->get('class', '') }}"
>
    @isset($label)
        <div class="mb-2 leading-none space-x-1">
            @isset($id)
                <label for="{{ $id }}" class="font-medium cursor-pointer text-grey-800">
                    {{ ucfirst($label) }}
                </label>
            @else
                <span class="font-medium text-grey-700">
                    {{ ucfirst($label) }}
                </span>
            @endisset

            @if(isset($isRequired) && ($isRequired == 'true') | $isRequired == '1')
                <span class="text-sm leading-none label label-warning">Verplicht</span>
            @endif
        </div>
    @endisset

    @isset($description)
        <div class="prose prose-dark prose-editor">
            {!! $description !!}
        </div>
    @endisset

    <div class="mt-3">
        {{ $slot }}
    </div>

    @isset($name)
        <div class="mt-2">
            @error($name)
                <x-chief-inline-notification type="error">
                    {{ $message }}
                </x-chief-inline-notification>
            @enderror
        </div>

        <div data-error-placeholder="{{ $name }}" class="hidden mt-2">
            <x-chief-inline-notification type="error">
                <div data-error-placeholder-content></div>
            </x-chief-inline-notification>
        </div>
    @endisset
</div>
