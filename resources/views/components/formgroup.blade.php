<div
    {!! $attributes->has('data-conditional') ? 'data-conditional="' . $attributes->get('data-conditional') . '"' : null !!}
    {!! $attributes->has('data-conditional-trigger-type') ? 'data-conditional-trigger-type="' . $attributes->get('data-conditional-trigger-type') . '"' : null !!}
    {!! $attributes->get('data-conditional-data') ? 'data-conditional-data="' . $attributes->get('data-conditional-data') . '"' : null !!}
    class="{{ $attributes->get('class', '') }}"
>
    {{-- Check if label exists and if it has a useful value --}}
    @if(isset($label) && $label)
        <div class="mb-2 leading-none space-x-1">
            <span class="font-medium text-grey-700">
                {{ ucfirst($label) }}
            </span>
            @if(isset($isRequired) && ($isRequired == 'true') | $isRequired == '1')
                <span class="text-sm leading-none label label-warning">Verplicht</span>
            @endif
        </div>
    @endif

    @isset($description)
        <div class="prose prose-dark prose-editor">
            {!! $description !!}
        </div>
    @endisset

    <div class="{{ isset($label) && $label ? 'mt-3' : null }}">
        {{ $slot }}
    </div>
    @if(isset($field) && count($field->getLocales()) > 0)
        @foreach($field->getLocales() as $locale)
            @error($field->getId($locale))
                <div class="mt-2">
                    <x-chief-inline-notification type="error">
                        {{ $message }}
                    </x-chief-inline-notification>
                </div>
            @enderror

            <div data-error-placeholder="{{ $field->getId($locale) }}" class="hidden mt-2">
                <x-chief-inline-notification type="error">
                    <div data-error-placeholder-content></div>
                </x-chief-inline-notification>
            </div>
        @endforeach
    @elseif( isset($name) )
        @if(isset($errors))
            @error($name)
                <div class="mt-2">
                    <x-chief-inline-notification type="error">
                        {{ $message }}
                    </x-chief-inline-notification>
                </div>
            @enderror
        @endif

        <div data-error-placeholder="{{ $name }}" class="hidden mt-2">
            <x-chief-inline-notification type="error">
                <div data-error-placeholder-content></div>
            </x-chief-inline-notification>
        </div>
    @endif
</div>
