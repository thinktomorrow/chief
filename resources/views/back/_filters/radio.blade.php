<div class="stack-s">
    @if($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    @foreach($options as $option => $optionLabel)
        <label class="block stack-xs custom-indicators" for="{{ $id.'-'.$option }}">
            <input {{ ($option == $value) ? 'checked="checked"':'' }}
                   name="{{ $name }}"
                   value="{{ $option }}"
                   id="{{ $id.'-'.$option }}"
                   type="radio">
            <span class="custom-radiobutton"></span>
            <strong>{{ $optionLabel }}</strong>
        </label>
    @endforeach

    @if($description)
        <p class="stack-xs squished-xs font-s">{{ $description }}</p>
    @endif
</div>
