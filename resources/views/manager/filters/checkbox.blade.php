<x-chief-formgroup
        label="{{ $label ?? '' }}"
        name="{{ $name }}"
>
    @if(isset($description))
        <x-slot name="description">
            <p>{!! $description !!}</p>
        </x-slot>
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
</x-chief-formgroup>
