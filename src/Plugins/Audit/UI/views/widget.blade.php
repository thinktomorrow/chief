<div x-data class="w-full md:w-1/2">
    <x-chief::window>
        <h2>Historiek</h2>
        @forelse ($events as $event)
            @php
                $detail = $event->models->first(fn ($model) => $model->changes || $model->context);
                $kind = $detail ? 'model' : ($event->context || ! $event->recorded_at->equalTo($event->occurred_at) || $history->missingMailPreview($event) ? 'event' : ($event->visible_rich_data ? 'rich' : null));
            @endphp
            <div class="border-grey-100 border-b py-2">
                @if ($kind)
                    <button
                        type="button"
                        x-on:click="$dispatch('audit:show-detail', { kind: '{{ $kind }}', eventId: '{{ $event->getKey() }}', modelId: '{{ $detail?->getKey() }}' })"
                    >
                        @include ('chief-audit::event-line', ['compact' => true])
                    </button>
                @else
                    <a href="{{ route('chief.audit.index', $filters) }}">
                        @include ('chief-audit::event-line', ['compact' => true])
                    </a>
                @endif
            </div>
        @empty
            <p>Geen historiek.</p>
        @endforelse
        <a href="{{ route('chief.audit.index', $filters) }}">Alle historiek</a>
    </x-chief::window>
    <livewire:chief-audit::details />
</div>
