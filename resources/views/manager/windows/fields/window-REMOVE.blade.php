<livewire:fields-window
    :model="$model"
    :tag="$tagged ?? 'untagged'"
    :title="$title ?? ''"
    :slot="$slot->__toString()"
/>
