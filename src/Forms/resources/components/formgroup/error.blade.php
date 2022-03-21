@props([
    'errorIds' => [],
])

@php

    $errorIds = (array) $errorIds;

    // When no errorIds are passed, we assume this formgroup is used by a form component.
    if(count($errorIds) < 1) {
        if ($hasLocales()) {
            foreach ($getLocales() as $locale) {
                $errorIds[] = $getId($locale);
            }
        } else {
            $errorIds = [$getId()];
        }
    }

@endphp

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
