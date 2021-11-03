<?php

$errorIds = [];

if (isset($field)) {
    if (count($field->getLocales()) > 0) {
        foreach ($field->getLocales() as $locale) {
            $errorIds[] = $field->getId($locale);
        }
    } else {
        $errorIds = [$field->getId()];
    }
} else {
    $errorIds = isset($name) ? [$name] : [];
}

?>

@foreach($errorIds as $errorId)
    @if(isset($errors))
        @error($errorId)
        <div class="mt-2">
            <x-chief-inline-notification type="error">
                {{ $message }}
            </x-chief-inline-notification>
        </div>
        @enderror
    @endif

    <div data-error-placeholder="{{ $errorId }}" class="hidden mt-2">
        <x-chief-inline-notification type="error">
            <div data-error-placeholder-content></div>
        </x-chief-inline-notification>
    </div>
@endforeach
