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
        <input type="text" name="{{ $name ?? $key }}" id="{{ $key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ old($key, $manager->getFieldValue($key)) }}">
        <error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
    </div>
</section>
