<div class="stack-s">
    @if($filter->label)
        <label for="{{ $filter->key }}">{{ $filter->label }}</label>
    @endif
    <input type="text" name="{{ $filter->name }}" id="{{ $filter->key }}" class="input inset-s" placeholder="{{ $filter->placeholder ?? '' }}" value="{{ old($filter->name, $filter->default) }}">

    @if($filter->description)
        <p class="stack-xs squished-xs font-s">{{ $filter->description }}</p>
    @endif
</div>
