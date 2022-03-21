<x-chief-form::formgroup id="{{ $id }}" label="{{ $label ?? null }}">
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
    <x-chief-form::formgroup.error error-ids="{{ $id }}"></x-chief-form::formgroup.error>
</x-chief-form::formgroup>
