<div class="w-full md:w-1/2">
    <x-chief::window>
        <h2>Historiek</h2>
        @forelse ($events as $event)
            @php
                $detail = $event->models->first(fn ($model) => $model->changes || $model->context);
                $url = $detail
                    ? route('chief.audit.details', [$event->getKey(), $detail->getKey()])
                    : ($event->context || ! $event->recorded_at->equalTo($event->occurred_at)
                        ? route('chief.audit.event-details', $event->getKey())
                        : route('chief.audit.index', $filters));
            @endphp
            <div class="border-grey-100 border-b py-2">
                <a href="{{ $url }}">
                    <time
                        datetime="{{ $event->occurred_at->toIso8601String() }}"
                        >{{ $event->occurred_at->setTimezone($timezone)->format('d/m H:i') }}</time
                    >
                    <span>{{ $event->actor_snapshot['name'] }}</span>
                    <span>{{ config('chief.audit.types.' . $event->type . '.label', $event->type) }}</span>
                    @if ($event->summary)
                        <span>{{ $event->summary }}</span>
                    @endif
                    @if ($event->model_snapshot)
                        <span>{{ $event->model_snapshot['name'] }}</span>
                    @endif
                </a>
            </div>
        @empty
            <p>Geen historiek.</p>
        @endforelse
        <a href="{{ route('chief.audit.index', $filters) }}">Alle historiek</a>
    </x-chief::window>
</div>
