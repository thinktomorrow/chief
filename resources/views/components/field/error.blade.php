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
@elseif( isset($error) )
    @error($error)
    <div class="mt-2">
        <x-chief-inline-notification type="error">
            {{ $message }}
        </x-chief-inline-notification>
    </div>
    @enderror

    <div data-error-placeholder="{{ $error }}" class="hidden mt-2">
        <x-chief-inline-notification type="error">
            <div data-error-placeholder-content></div>
        </x-chief-inline-notification>
    </div>
@endif
