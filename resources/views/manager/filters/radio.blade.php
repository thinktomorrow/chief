<x-chief-form::formgroup id="{{ $id }}" label="{{ $label ?? null }}">
    @if(isset($description))
        <x-slot name="description">
            <p>{!! $description !!}</p>
        </x-slot>
    @endif

    <div class="space-y-1">
        @foreach($options as $option => $optionLabel)
            <label class="with-radio" for="{{ $id.'-'.$option }}">
                <input
                    {{ ($option == ($value ?: $default)) ? 'checked="checked"':'' }}
                    name="{{ $name }}"
                    value="{{ $option }}"
                    id="{{ $id.'-'.$option }}"
                    type="radio"
                >
                <span>{!! $optionLabel !!}</span>
            </label>
        @endforeach
    </div>
</x-chief-form::formgroup>
