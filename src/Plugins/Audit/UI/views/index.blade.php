<x-chief::page.template title="Historiek" container="md">
    <x-chief::window>
        <h1>Historiek</h1>
        <p>{{ $events->total() }} resultaten</p>
        <form method="get">
            <input name="search" value="{{ request('search') }}" aria-label="Zoeken" />
            <select name="model_type" aria-label="Modeltype">
                <option value="">Alle modeltypes</option>
                @foreach ($options['model_types'] as $type)
                    <option value="{{ $type }}" @selected (request('model_type') === $type)>{{ $type }}</option>
                @endforeach
            </select>
            <button type="submit">Zoeken</button>
        </form>

        @forelse ($events as $event)
            <article>
                <time
                    datetime="{{ $event->occurred_at->toIso8601String() }}"
                    >{{ $event->occurred_at->format('d/m/Y H:i') }}</time
                >
                <span>{{ $event->actor_snapshot['name'] }}</span>
                <span>{{ $event->type }}</span>
                @if ($event->summary)
                    <span>{{ $event->summary }}</span>
                @endif
                @if ($event->outcome)
                    <span>{{ $event->outcome }}</span>
                @endif
                @if ($event->model_snapshot)
                    <span>{{ $event->model_snapshot['name'] }}</span>
                @endif
                @foreach ($event->models as $model)
                    @if ($model->changes)
                        <a href="{{ route('chief.audit.details', [$event->getKey(), $model->getKey()]) }}"
                            >Wijzigingen: {{ $model->model_snapshot['name'] }}</a
                        >
                    @elseif ($model->getKey() !== $event->models->first()?->getKey())
                        <span>{{ $model->model_snapshot['name'] }}</span>
                    @endif
                @endforeach
            </article>
        @empty
            <p>Geen historiek.</p>
        @endforelse

        {{ $events->links() }}
    </x-chief::window>
</x-chief::page.template>
