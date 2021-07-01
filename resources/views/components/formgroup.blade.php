<div
    {!! $attributes->has('data-formgroup') ? 'data-formgroup="' . $attributes->get('data-formgroup') . '"' : null !!}
    {!! $attributes->get('data-trigger-formgroup') ? 'data-trigger-formgroup="' . $attributes->get('data-trigger-formgroup') . '"' : null !!}
    {!! $attributes->get('data-trigger-formgroup-with-value') ? 'data-trigger-formgroup-with-value="' . $attributes->get('data-trigger-formgroup-with-value') . '"' : null !!}
    class="{{ $attributes->get('class', '') }}"
>
    @isset($label)
        <div class="mb-3 space-x-1 leading-none">
            @isset($id)
                <label for="{{ $id }}" class="font-medium leading-none cursor-pointer text-grey-900">
                    {{ ucfirst($label) }}
                </label>
            @else
                <span class="font-medium leading-none text-grey-900">
                    {{ ucfirst($label) }}
                </span>
            @endisset

            @if(isset($isRequired) && ($isRequired == 'true') | $isRequired == '1')
                <span class="text-sm leading-none label label-warning">Verplicht</span>
            @endif
        </div>
    @endisset

    @isset($description)
        <div class="mb-4 prose prose-dark prose-editor">
            {{ $description }}
        </div>
    @endisset

    <div>
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

        <div data-error-placeholder="{{ $name }}" class="hidden mt-2">
            <x-inline-notification type="error">
                <div data-error-placeholder-content></div>
            </x-inline-notification>
        </div>

    @endisset


</div>
