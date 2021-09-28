@if($fieldWindow->hasView())
    @include($fieldWindow->getView(), array_merge(get_defined_vars(), $fieldWindow->getViewData()))
@else
    <livewire:fields_component
        :model="$model"
        :componentKey="$fieldWindow->getId()"
        :title="$fieldWindow->getTitle()"
        class="window window-white window-md"
    />
@endif
