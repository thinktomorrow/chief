<x-chief::button x-on:click="$dispatch('open-dialog', { 'id': 'table-column-selection' })" variant="outline-white">
    <x-chief::icon.layout-column />
</x-chief::button>

<x-chief::dialog.dropdown id="table-column-selection" placement="bottom-end">
    <div class="min-w-48 space-y-3.5 p-3.5">
        <div class="space-y-2">
            @foreach ($this->getOptionsForColumnSelection() as $columnSelection)
                @php
                    $radioId = 'js-trigger-' . mt_rand(0, 9999);
                @endphp

                <label for="{{ $radioId }}" class="flex items-start gap-2">
                    <x-chief::form.input.checkbox
                        wire:model.live="columnSelection"
                        :disabled="$columnSelection['disabled']"
                        id="{{ $radioId }}"
                        value="{{ $columnSelection['key'] }}"
                    />

                    <span class="body body-dark leading-5">{!! $columnSelection['label'] !!}</span>
                </label>

            @endforeach
        </div>

        <div class="flex items-start justify-between gap-2">
            <x-chief::button x-on:click="close()" size="sm" variant="grey">Ok</x-chief::button>
        </div>
    </div>
</x-chief::dialog.dropdown>
