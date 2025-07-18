<x-chief::dialog.drawer wired>
    @if ($isOpen)
        @php
            $badges = [];
            if (! $fragment->isOnline) {
                $badges[] = ['label' => 'Offline', 'variant' => 'grey'];
            }
            if ($fragment->isShared) {
                $badges[] = ['label' => 'Gedeeld', 'variant' => 'blue'];
            }
        @endphp

        <x-slot name="header">
            <x-chief::dialog.drawer.header :title="ucfirst($fragment->label)" :badges="$badges">
                <div class="flex items-start gap-2">
                    <x-chief::button
                        variant="outline-white"
                        type="button"
                        class="shrink-0"
                        x-on:click="$dispatch('open-dialog', { 'id': 'fragment-actions-{{ $fragment->getId() }}' })"
                    >
                        <span>Acties</span>
                        <x-chief::icon.more-vertical-circle />
                    </x-chief::button>

                    <x-chief::dialog.dropdown id="fragment-actions-{{ $fragment->getId() }}">
                        @include('chief-fragments::livewire._partials.status-fragment-actions')
                        @include('chief-fragments::livewire._partials.delete-fragment-action')
                    </x-chief::dialog.dropdown>
                </div>
            </x-chief::dialog.drawer.header>
        </x-slot>

        {{-- TODO(ben): get fragment urls --}}
        @include('chief-fragments::livewire._partials.shared-fragment-actions')

        @include('chief-form::livewire._partials.locale-toggle', ['entangleScopedLocale' => true])

        @include('chief-fragments::livewire._partials.bookmarks')

        @foreach ($this->getFields() as $field)
            {{ $field }}
        @endforeach

        @if ($fragment->allowsFragments)
            <x-chief::form.fieldset>
                <x-chief::form.label>Fragmenten</x-chief::form.label>

                <div
                    data-slot="control"
                    wire:ignore.self
                    x-sortable
                    x-sortable-group="{{ 'group-fragment-' . $fragment->fragmentId }}"
                    x-sortable-ghost-class="fragment-sort-ghost"
                    x-sortable-drag-class="fragment-sort-drag"
                    x-on:end.stop="$wire.reorder($event.target.sortable.toArray())"
                    class="border-grey-100 [&>[data-slot=fragment]+[data-slot=fragment]]:border-grey-100 rounded-xl border px-4 [&>[data-slot=fragment]+[data-slot=fragment]]:border-t"
                >
                    @if ($fragments->count() > 0)
                        @include(
                            'chief-fragments::livewire._partials.add-fragment-button',
                            [
                                'order' => -1,
                                'parentId' => $fragment->fragmentId,
                            ]
                        )

                        @foreach ($fragments as $childFragment)
                            @include(
                                'chief-fragments::livewire._partials.fragment',
                                [
                                    'fragment' => $childFragment,
                                    'parentId' => $fragment->fragmentId,
                                ]
                            )
                        @endforeach
                    @else
                        @include(
                            'chief-fragments::livewire._partials.empty-context',
                            [
                                'parentId' => $fragment->fragmentId,
                            ]
                        )
                    @endif
                </div>

                <template x-teleport="body">
                    <livewire:chief-fragments::edit-fragment
                        :key="$fragment->getId() . '-edit-fragment'"
                        :context="$context"
                        :model="$this->getModel()"
                        :parent-component-id="$this->getId()"
                    />
                </template>

                <template x-teleport="body">
                    <livewire:chief-fragments::add-fragment
                        :key="$fragment->getId() . '-add-fragment'"
                        :context="$context"
                        :parent-component-id="$this->getId()"
                    />
                </template>
            </x-chief::form.fieldset>
        @endif

        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button wire:click="save" wire:loading.attr="disabled" variant="blue">
                    <span>Bewaren</span>
                    <x-chief::icon.loading wire:loading.delay class="animate-spin" />
                </x-chief::button>
                <x-chief::button wire:click="close">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>
    @endif
</x-chief::dialog.drawer>
