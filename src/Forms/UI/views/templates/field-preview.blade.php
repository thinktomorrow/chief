@php
    $component->ignoreDefault();
@endphp

<x-chief::form.preview :label="ucfirst($getLabel())">
    @if($getScopedLocale())
        @include($getPreviewView(), ['component' => $component, 'locale' => $getScopedLocale()])
    @else
        @include($getPreviewView())
    @endif
</x-chief::form.preview>
