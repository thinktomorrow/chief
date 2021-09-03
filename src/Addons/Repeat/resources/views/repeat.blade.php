@php
    // Avoid confusion in foreach loop below.
    $repeatField = $field;
    $repeatedFields = $repeatField->getRepeatedFields()->all();

    $uniqueContainerId = $repeatField->getKey() . \Illuminate\Support\Str::random(8);
@endphp

<div data-repeat-container="{{ $uniqueContainerId }}">
    <div data-repeat-fields="{{ $uniqueContainerId }}" class="rounded-lg bg-grey-100">
        @foreach($repeatedFields as $i => $fieldSet)
            <fieldset 
                id="{{ $fieldSet->getId() }}" 
                data-repeat-fieldset="{{ $uniqueContainerId }}" 
                class="p-4 bg-white rounded-lg"
            >
                <div class="flex" style="margin-bottom:-3px;">
                    <span>{{ $repeatField->getLabel() }}</span>
                    <div data-repeat-delete="{{ $uniqueContainerId }}" class="rounded cursor-pointer squished-s text-error hover:bg-grey-50 center-y" style="margin-left:auto;">
                        <svg class="mr-2" width="18" height="18"><use data-v-3997f6a0="" xlink:href="#trash"></use></svg>
                    </div>
                </div>
                <div class="inset space-y-4">
                    @foreach($fieldSet->all() as $field)
                        <div class="mb-4">
                            <x-chief-formgroup
                                label="{{ $field->getLabel() }}"
                                name="{{ $field->getName($locale ?? null) }}"
                                id="{{ $field->getDottedName() }}"
                            >
                                <div data-repeat-field="{{ $uniqueContainerId }}" data-repeat-field-key="{{ $field->getDottedName() }}">
                                    {!! $field->render() !!}
                                </div>
                            </x-chief-formgroup>
                        </div>
                    @endforeach
                </div>
            </fieldset>
        @endforeach
    </div>
    <div class="relative flex items-center justify-center w-full h-8 center-y z-1">
        <span data-repeat-add="{{ $uniqueContainerId }}" class="block mx-auto rounded-full cursor-pointer menu-trigger bg-secondary-50 hover:text-secondary-600">
            <svg width="24" height="24" class="fill-current"><use xlink:href="#plus"/></svg>
        </span>
    </div>
</div>

@push('custom-scripts-after-vue')
    <script>
        new RepeatField('{{ $uniqueContainerId }}');
    </script>
@endpush
