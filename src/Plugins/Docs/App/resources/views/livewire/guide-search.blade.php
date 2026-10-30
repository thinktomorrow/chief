<div
    x-data="{
        selected: 0,
        init() {
            this.root = this.$el;
        },
        results() {
            return [...this.root.querySelectorAll('[data-guide-search-result]')];
        },
        move(offset) {
            const results = this.results();

            if (results.length === 0) {
                return;
            }

            this.selected = (this.selected + offset + results.length) % results.length;
            results[this.selected].scrollIntoView({ block: 'nearest' });
        },
        openSelected() {
            this.results()[this.selected]?.click();
        },
        focusQuery() {
            this.root.querySelector('[data-guide-search-query]').focus();
        },
    }"
    x-on:keydown.window.meta.k.prevent="$dispatch('open-dialog', { id: 'guide-search' })"
    x-on:keydown.window.ctrl.k.prevent="$dispatch('open-dialog', { id: 'guide-search' })"
    x-on:dialog-opened.window="$event.detail.id === 'guide-search' && $nextTick(() => focusQuery())"
>
    <x-chief::dialog.modal id="guide-search" size="sm" x-on:keydown.escape.window="close()">
        <x-slot name="header" class="p-4">
            <x-chief::form.input.search
                data-guide-search-query
                wire:model.live.debounce.250ms="query"
                placeholder="Zoek in de handleiding…"
                aria-label="Zoek in de handleiding"
                autocomplete="off"
                x-on:input="selected = 0"
                x-on:keydown.down.prevent="move(1)"
                x-on:keydown.up.prevent="move(-1)"
                x-on:keydown.enter.prevent="openSelected()"
            />
        </x-slot>

        @if ($isTooShort)
            <p class="body text-grey-500 text-center">Typ minstens {{ \Thinktomorrow\Chief\Plugins\Docs\Search\GuideSearch::MIN_QUERY_LENGTH }} tekens om in de handleiding te zoeken.</p>
        @elseif ($results === [])
            <p class="body text-grey-500 text-center">Niets gevonden voor “{{ trim($query) }}”.</p>
        @else
            <ul class="-mx-2 -mb-2">
                @foreach ($results as $result)
                    <li wire:key="{{ $result->url }}">
                        <a
                            href="{{ $result->url }}"
                            data-guide-search-result
                            x-on:click="close()"
                            x-on:mouseenter="selected = {{ $loop->index }}"
                            x-bind:class="selected === {{ $loop->index }} && 'bg-grey-50'"
                            class="block space-y-1 rounded-lg px-3 py-2"
                        >
                            @if ($result->section)
                                <span class="text-grey-500 block text-xs font-medium">{{ $result->section }}</span>
                            @endif

                            <span class="text-grey-950 block font-medium">{{ $result->title }}</span>

                            <span
                                class="body text-grey-500 [&_mark]:bg-primary-50 [&_mark]:text-grey-950 [&_mark]:rounded-sm block text-sm"
                            >
                                {{ $result->snippet }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-chief::dialog.modal>
</div>
