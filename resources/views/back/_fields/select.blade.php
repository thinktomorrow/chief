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

        <chief-multiselect
                name="{{ $key }}"
                :options='@json($field->options)'
                selected='@json(old($key, $field->selected ?? $manager->getFieldValue($key)))'
                :multiple='@json(!!$field->multiple)'
        >
        </chief-multiselect>

        <error class="caption text-warning" field="{{ $key }}" :errors="errors.all()"></error>
    </div>
</section>
