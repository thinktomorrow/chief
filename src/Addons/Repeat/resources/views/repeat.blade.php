@php
    // Avoid confusion in foreach loop below.
    $repeatField = $field;
    $repeatedFields = $repeatField->getRepeatedFields()->all();

    $uniqueContainerId = $repeatField->getKey() . \Illuminate\Support\Str::random(8);
@endphp

<div data-repeat-container="{{ $uniqueContainerId }}" class="relative">
    <div class="p-4">
        <div
            class="{{ $repeatField->prefersCompactLayout() ? '' : 'border divide-y divide-grey-100 border-grey-100' }} rounded-lg -window-xs"
            data-repeat-fields="{{ $uniqueContainerId }}"
        >
            @foreach($repeatedFields as $i => $fieldSet)
                <fieldset
                    id="{{ $fieldSet->getId() }}"
                    data-repeat-fieldset="{{ $uniqueContainerId }}"
                    class="flex {{ $repeatField->prefersCompactLayout() ? 'mb-2' : 'p-4' }} {{ $fieldSet->count() == 1 ? 'items-center' : '' }}"
                >
                    <div class="w-full">
                        <div class="row-start-start gutter-3">
                            @foreach($fieldSet->all() as $field)
                                @component('chief::manager.fields.form.field', [
                                    'field' => $field,
                                    'autofocus' => (isset($index) && $index === 0),
                                ])
                                    <div
                                        data-repeat-field="{{ $uniqueContainerId }}"
                                        data-repeat-field-key="{{ $field->getDottedName() }}"
                                    >
                                        {!! $field->render() !!}
                                    </div>
                                @endcomponent
                            @endforeach
                        </div>
                    </div>

                    <span
                        data-repeat-delete="{{ $uniqueContainerId }}"
                        class="flex-shrink-0 ml-3 cursor-pointer link link-grey-light"
                        style="margin-top: -3px;"
                    >
                        <x-chief-icon-label type="delete"></x-chief-icon-label>
                    </span>
                </fieldset>
            @endforeach
        </div>
    </div>

    <span
        data-repeat-add="{{ $uniqueContainerId }}"
        class="cursor-pointer link link-primary {{ $repeatField->prefersCompactLayout() ? 'mt-2' : 'mt-6' }}"
    >
        <x-chief-icon-label type="add">Nieuw veld toevoegen</x-chief-icon-label>
    </span>
</div>
