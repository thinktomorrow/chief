@if($fieldWindow->hasView())
    @include($fieldWindow->getView())
@else
    <livewire:fields_component
        :model="$model"
        :componentKey="$fieldWindow->getId()"
        :title="$fieldWindow->getTitle()"
        class="window window-white window-md"
    />
@endif
