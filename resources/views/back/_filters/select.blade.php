<div class="stack-s">
    @if($filter->label)
        <label for="{{ $filter->key }}">{{ $filter->label }}</label>
    @endif

    <chief-multiselect
        name="{{ $filter->name }}"
        :options='@json($filter->options)'
        selected='@json(old($filter->name, $filter->selected ?? $filter->default))'
        :multiple='@json(!!$filter->allowMultiple())'
    >
    </chief-multiselect>

    @if($filter->description)
        <p class="stack-xs squished-xs font-s">{{ $filter->description }}</p>
    @endif
</div>
