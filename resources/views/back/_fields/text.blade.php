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
        <textarea class="inset-s" name="{{ $name ?? $key }}" id="{{ $key }}" cols="5" rows="5" style="resize: vertical;">{{ old($key, $manager->getFieldValue($key)) }}</textarea>
        <error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
    </div>
</section>
