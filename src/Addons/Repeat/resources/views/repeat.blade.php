@php
    // Avoid confusion in foreach loop below.
    $repeatField = $field;
    $repeatedFields = $repeatField->getRepeatedFields()->all();

    $uniqueContainerId = $repeatField->getKey() . \Illuminate\Support\Str::random(8);
@endphp

<div data-repeat-container="{{ $uniqueContainerId }}" class="relative">
    <div class="window window-white window-xs">
        <div 
            class="border rounded-lg -window-xs divide-y divide-grey-100 border-grey-100"
            data-repeat-fields="{{ $uniqueContainerId }}"
        >
            @foreach($repeatedFields as $i => $fieldSet)
                <fieldset
                    id="{{ $fieldSet->getId() }}"
                    data-repeat-fieldset="{{ $uniqueContainerId }}"
                    class="flex window-xs"
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
                        class="flex-shrink-0 ml-6 cursor-pointer link link-error"
                        style="margin-top: -3px;"
                    >
                        <x-chief-icon-label type="delete"></x-chief-icon-label>
                    </span>
                </fieldset>
            @endforeach
        </div>
    </div>

    <!-- plus icon -->
    <div
        data-repeat-add="{{ $uniqueContainerId }}"
        class="absolute left-0 right-0 flex justify-center h-8 border-none cursor-pointer z-1 icon-label"
        style="margin-top: -12px;"
    >
        <div class="absolute bg-white rounded-full link link-black icon-label-icon">
            <svg width="24" height="24"> <use xlink:href="#icon-add-circle"/> </svg>
        </div>
    </div>
</div>
