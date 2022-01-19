@php
    // Avoid confusion in foreach loop below.
    $repeatField = $field;
    $repeatedFields = $repeatField->getRepeatedFields();

    $uniqueContainerId = $repeatField->getKey() . \Illuminate\Support\Str::random(8);
@endphp

<div data-repeat-container="{{ $uniqueContainerId }}" class="relative">
    <div class="p-4">
        <div
            class="{{ $repeatField->prefersCompactLayout() ? 'divide-y divide-grey-100' : 'border divide-y divide-grey-100 border-grey-100' }} rounded-lg -m-4"
            data-repeat-fields="{{ $uniqueContainerId }}"
        >
            @foreach($repeatedFields as $i => $fields)
                <fieldset
                    id="{{ $fieldSetId = \Illuminate\Support\Str::random(8) }}"
                    data-repeat-fieldset="{{ $uniqueContainerId }}"
                    class="flex items-center {{ $repeatField->prefersCompactLayout() ? 'py-2' : 'p-4' }}"
                >
                    <div class="w-full">
                        <div class="row-start-start gutter-3">
                            @foreach($fields->all() as $field)
                                @if($field instanceof \Thinktomorrow\Chief\Forms\Fields\Types\HiddenField)
                                    {!! $field->render() !!}
                                @else
                                    <x-chief::field.form :field="$field">
                                        <div
                                            data-repeat-field="{{ $uniqueContainerId }}"
                                            data-repeat-field-key="{{ $field->getDottedName() }}"
                                        >
                                            {!! $field->render(['repeat' => [
                                                'unique_container_id' => $uniqueContainerId,
                                                'fieldset_id' => $fieldSetId,
                                            ]]) !!}
                                        </div>
                                    </x-chief::field.form>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <span
                        data-repeat-delete="{{ $uniqueContainerId }}"
                        class="flex-shrink-0 ml-3 transform cursor-pointer link link-grey-light transition-150"
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
        class="cursor-pointer link link-primary {{ $repeatField->prefersCompactLayout() ? 'mt-4' : 'mt-6' }}"
    >
        <x-chief-icon-label type="add">Nieuw veld toevoegen</x-chief-icon-label>
    </span>
</div>
