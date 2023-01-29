@props([
    'for' => null,
    'label' => null,
    'showAsToggle' => false,
])

<label for="{{ $for }}" @class([
    'with-checkbox' => !$showAsToggle,
    'with-toggle' => $showAsToggle,
])>
    {{ $slot }}

    @if($showAsToggle)
        <span class="toggle-slider"></span>
    @endif

    @if($label)
        <span @class(['body body-dark', 'mt-0.5 ml-3' => $showAsToggle])>{!! $label !!}</span>
    @endif
</label>
