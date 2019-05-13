<section class="row formgroup stack gutter-l bg-white">
    <div class="column-4">
        @if($field->label)
            <h2 class="formgroup-label"><label for="{{ $key }}">{{ ucfirst($field->label) }}</label></h2>
        @endif

        @if($field->description)
            <p>{!! $field->description !!}</p>
        @endif
    </div>
    <div class="formgroup-input column-8">

        @foreach($fields->all() as $field)
            <label for="{{ $field->key }}">{{ $field->label }}</label>
            <div class="input-addon stack-xs">
                @if($field->prepend)
                    <div class="addon inset-s">{{ $field->prepend }}</div>
                @endif
                <input type="text" name="{{ $field->name }}" id="{{ $field->key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ $field->value() }}">
            </div>
            @if($field->description)
                <p>{!! $field->description !!}</p>
            @endif
        @endforeach
        <error class="caption text-warning" field="url-slugs" :errors="errors.all()"></error>

    </div>
</section>
