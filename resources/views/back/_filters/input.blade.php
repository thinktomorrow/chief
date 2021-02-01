<div class="stack-s">
    @if($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif
    <input type="text" name="{{ $name }}" id="{{ $id }}" class="input inset-s" placeholder="{{ $placeholder }}" value="{{ $value }}">

    @if($description)
        <p class="stack-xs squished-xs font-s">{{ $description }}</p>
    @endif
</div>
