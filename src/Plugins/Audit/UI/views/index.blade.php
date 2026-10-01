<x-chief::page.template title="Historiek" container="md">
    <x-chief::window>
        <h1>Historiek</h1>

        @forelse ($events as $event)
            <article>
                <time
                    datetime="{{ $event->occurred_at->toIso8601String() }}"
                    >{{ $event->occurred_at->format('d/m/Y H:i') }}</time
                >
                <span>{{ $event->actor_snapshot['name'] }}</span>
                <span>{{ $event->type }}</span>
                <span>{{ $event->summary }}</span>
                @if ($event->outcome)
                    <span>{{ $event->outcome }}</span>
                @endif
                @if ($event->model_snapshot)
                    <span>{{ $event->model_snapshot['name'] }}</span>
                @endif
            </article>
        @empty
            <p>Geen historiek.</p>
        @endforelse

        {{ $events->links() }}
    </x-chief::window>
</x-chief::page.template>
