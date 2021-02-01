<div class="stack-s">
    @if($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    @foreach($options as $option => $optionLabel)
        <label class="block stack-xs custom-indicators" for="{{ $id.'-'.$option }}">
            <input {{ in_array($option, $value) ? 'checked="checked"':'' }}
                   name="{{ $name }}[]"
                   value="{{ $option }}"
                   id="{{ $id.'-'.$option }}"
                   type="checkbox">
            <span class="custom-checkbox"></span>
            <strong>{{ $optionLabel }}</strong>
        </label>
    @endforeach

    @if($description)
        <p class="stack-xs squished-xs font-s">{{ $description }}</p>
    @endif
</div>
