<div class="stack-s">
    @if($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    <chief-multiselect id="{{ $id }}"
        name="{{ $name }}"
        :options='@json($options)'
        selected='@json($value)'
        :multiple='@json($multiple)'
    >
    </chief-multiselect>

    @if($description)
        <p class="stack-xs squished-xs font-s">{{ $description }}</p>
    @endif
</div>
