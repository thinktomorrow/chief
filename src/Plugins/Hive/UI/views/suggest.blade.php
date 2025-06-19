@php
    $hiveConfig = [
        'wireModel' => \Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName::get($getName($locale ?? null)),
        'dialogId' => 'hive-suggest-modal-' . $getElementId($locale ?? null),
        'payload' => $payload ?? [],
    ];
@endphp

<div
    x-data="hiveAssistant(@js($hiveConfig))"
    class="relative"
>
    <button
        type="button"
        @click="$dispatch('open-dialog', { id: 'hive-suggest-modal-{{ $getElementId($locale ?? null) }}' })"
        class="link cursor-pointer link-sm link-body-dark underline"
        x-text="loading ? '...' : 'Hive suggestie'"
    ></button>

    <template x-teleport="body">
        <x-chief::dialog.modal id="hive-suggest-modal-{{ $getElementId($locale ?? null) }}" title="Suggesties"
                               size="xs">

            <x-chief::form.fieldset rule="form.prompt">
                <x-chief::form.label for="prompt"></x-chief::form.label>

                <div class="flex items-center gap-2 mb-2">
                    <x-chief::form.input.text id="prompt" x-model="text" />

                    <x-chief::button variant="blue" type="button" x-on:click="prompt()">
                        <x-chief::icon.search class="size-3" />
                    </x-chief::button>
                </div>

                <div class="mt-4">
                    @foreach($getHivePrompts() as $i => $prompt)
                        <x-chief::button
                            variant="grey"
                            size="sm"
                            class="shrink-0"
                            x-on:click.prevent="prompt('{{ str_replace('\\', '\\\\', $prompt::class) }}')"
                        >{{ $prompt->getLabel() }}</x-chief::button>
                    @endforeach
                </div>

            </x-chief::form.fieldset>

            <div x-show="suggestions.length > 0" class="prose prose-dark prose-spacing mt-4">
                <h3>Suggesties</h3>

                <ul>
                    <template x-for="suggestion in suggestions" :key="suggestion">
                        <x-chief::callout
                            size="sm"
                            class="text-left"
                            x-text="suggestion"
                            x-on:click="applySuggestion(suggestion)"
                        ></x-chief::callout>
                    </template>
                </ul>
            </div>

            <x-slot name="footer">
                <x-chief::dialog.modal.footer>
                    <x-chief::button variant="blue" type="button" x-on:click="close()">
                        Sluiten
                    </x-chief::button>
                </x-chief::dialog.modal.footer>
            </x-slot>
        </x-chief::dialog.modal>
    </template>
</div>
