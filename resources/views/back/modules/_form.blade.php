<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Interne titel</h2>
    </div>
    <div class="formgroup-input column-8">

        <input type="text" name="slug" id="slugField" class="input inset-s" placeholder="Interne titel" value="{{ old('slug', $module->slug) }}">

        <error class="caption text-warning" field="slug" :errors="errors.all()"></error>

    </div>
</section>

@if(count($module->customFields()) > 0)
    @foreach($module->customFields() as $field)
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                @if($field->label)
                    <h2 class="formgroup-label">{{ $field->label }}</h2>
                @endif

                @if($field->description)
                    <p>{{ $field->description }}</p>
                @endif
            </div>
            <div class="formgroup-input column-8">
                @include('chief::back._fields.customfield', [
                    'key'   => $field->key(),
                    'field' => $field,
                    'model' => $module
                ])
            </div>
        </section>
    @endforeach
@endif

@if(count($module->translatableFields()) > 0)
    @foreach($module->translatableFields() as $field)
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                @if($field->label)
                    <h2 class="formgroup-label">{{ $field->label }}</h2>
                @endif

                @if($field->description)
                    <p>{{ $field->description }}</p>
                @endif
            </div>
            <div class="formgroup-input column-8">
                @include('chief::back._fields.translatable_formgroup', [
                    'model' => $module,
                    'key' => $field->key(),
                ])
            </div>
        </section>
    @endforeach
@endif

@foreach($module->mediaFields() as $media)

    <?php

        $viewPath = (isset($media['is_document']) && $media['is_document'])
            ? 'chief::back._elements.mediagroup-documents'
            : 'chief::back._elements.mediagroup-images';

    ?>

    @include($viewPath, [
        'group' => $media['type'],
        'files' => $images[$media['type']],
        'label' => $media['label'],
        'description' => $media['description'],
        'multiple'    => $media['multiple'] ?? true
    ])
@endforeach
