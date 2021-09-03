<?php
    // Avoid confusion in foreach loop below.
    $repeatField = $field;
    $repeatedFields = $repeatField->getRepeatedFields()->all();

    $uniqueContainerId = $repeatField->getKey() . \Illuminate\Support\Str::random(8);
?>

<div data-repeat-container="{{ $uniqueContainerId }}">
    <div data-repeat-fields="{{ $uniqueContainerId }}">
        @foreach($repeatedFields as $i => $fieldSet)
            <fieldset id="{{ $fieldSet->getId() }}" data-repeat-fieldset="{{ $uniqueContainerId }}" class="border bg-white border-grey-100 rounded mb-4 p-4">
                <div class="flex" style="margin-bottom:-3px;">
                    <span>{{ $repeatField->getLabel() }}</span>
                    <div data-repeat-delete="{{ $uniqueContainerId }}" class="squished-s text-error hover:bg-grey-50 rounded center-y cursor-pointer {{ (count($repeatedFields) == 1) ? 'hidden' : '' }}" style="margin-left:auto;">
                        <svg class="mr-2" width="18" height="18"><use data-v-3997f6a0="" xlink:href="#trash"></use></svg>
                    </div>
                </div>
                <div class="inset">
                    @foreach($fieldSet->all() as $field)
                        <div class="mb-4">
                            <label for="{{ $field->getDottedName() }}">{{ $field->getLabel() }}</label>
                            <div data-repeat-field="{{ $field->getKey() }}">
                                {!! $field->render() !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </fieldset>
        @endforeach
    </div>
    <div class="flex justify-center items-center w-full h-8 center-y relative z-1">
        <span data-repeat-add="{{ $uniqueContainerId }}" class="block menu-trigger bg-secondary-50 rounded-full cursor-pointer mx-auto hover:text-secondary-600">
            <svg width="24" height="24" class="fill-current"><use xlink:href="#plus"/></svg>
        </span>
    </div>
</div>

@push('custom-scripts-after-vue')
    <script>
        new RepeatField('{{ $uniqueContainerId }}');
    </script>
@endpush
