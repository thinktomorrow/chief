<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Interne titel</h2>
    </div>
    <div class="formgroup-input column-8">

        <input type="text" name="slug" id="slugField" class="input inset-s" placeholder="Interne titel" value="{{ old('slug', $module->slug) }}">

        <error class="caption text-warning" field="slug" :errors="errors.all()"></error>

    </div>
</section>

<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Inhoud</h2>
    </div>
    <div class="formgroup-input column-8">
        @include('chief::back._elements.translatable_fieldgroups', [
            'model' => $module,
        ])
    </div>
</section>

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
    ])
@endforeach
