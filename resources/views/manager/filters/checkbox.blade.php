<x-chief-formgroup
        label="{{ $label ?? '' }}"
        name="{{ $name }}"
>
    @if(isset($description))
        <x-slot name="description">
            <p>{!! $description !!}</p>
        </x-slot>
    @endif

        <div class="space-y-1">
            @foreach($options as $option => $optionLabel)
                <label class="with-checkbox" for="{{ $id.'-'.$option }}">
                    <input {{ ($option == $value) ? 'checked="checked"':'' }}
                           name="{{ $name }}"
                           value="{{ $option }}"
                           id="{{ $id.'-'.$option }}"
                           type="checkbox">
                    <span>{!! $optionLabel !!}</span>
                </label>
            @endforeach
        </div>
</x-chief-formgroup>
