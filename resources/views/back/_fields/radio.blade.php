<section class="row formgroup stack gutter-l">
    <div class="column-4">
        @if($field->label)
            <h2 class="formgroup-label"><label for="{{ $key }}">{{ $field->label }}</label></h2>
        @endif

        @if($field->description)
            <p>{{ $field->description }}</p>
        @endif
    </div>
    <div class="formgroup-input column-8">

        <radio-options inline-template :errors="errors" default-type="{{ old($key, $field->selected) }}">
            <div>
                @foreach($field->options as $value => $label)
                    <label class="block stack-xs custom-indicators" for="{{ $key.'-'.$value }}">
                        <input v-on:click="changeType({{ $value }})" {{ old($key, $field->selected) == $value ? 'checked="checked"':'' }}
                        name="{{ $key }}"
                               value="{{ $value }}"
                               id="{{ $key.'-'.$value }}"
                               type="radio">
                        <span class="custom-radiobutton --primary"></span>
                        <strong>{{ $label }}</strong>
                    </label>
                @endforeach
            </div>
        </radio-options>

        <error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
    </div>
</section>
