@php
    $history = app(\Thinktomorrow\Chief\Plugins\Audit\VisibleHistory::class);
    $allEvents = $history->select(['model_type' => $auditModel->getMorphClass(), 'model_id' => (string) $auditModel->getKey()]);
    $recent = $allEvents->filter(fn ($event) => $history->priority($event) !== 'secondary')->take(5);
    $expanded = request()->query('audit_history') === 'all';
    $events = $expanded ? $allEvents : $recent;
@endphp

<x-chief::window title="Historiek">
    @forelse ($events->groupBy(fn ($event) => $event->occurred_at->setTimezone($history->timezone())->toDateString()) as $day => $dayEvents)
        <section class="space-y-2 border-b border-grey-100 py-3">
            <h3 class="text-sm font-semibold text-grey-700">{{ $dayEvents->first()->occurred_at->setTimezone($history->timezone())->format('d/m/Y') }}</h3>
            @foreach ($dayEvents as $event)
                @php ($presentation = config('chief.audit.types.' . $event->type, []))
                <article class="text-sm {{ $history->priority($event) === 'secondary' ? 'text-grey-500' : 'text-grey-900' }}">
                    <time datetime="{{ $event->occurred_at->toIso8601String() }}">{{ $event->occurred_at->setTimezone($history->timezone())->format('H:i') }}</time>
                    <span>{{ $event->actor_snapshot['name'] ?? 'Actor' }}</span>
                    <span>{{ $presentation['label'] ?? $event->type }}</span>
                    @if ($event->summary)
                        <span>{{ $event->summary }}</span>
                    @endif
                    @if ($event->outcome)
                        <span>{{ $event->outcome }}</span>
                    @endif
                    @if ($event->model_snapshot)
                        <span>{{ $event->model_snapshot['name'] ?? '' }}</span>
                    @endif
                </article>
            @endforeach
        </section>
    @empty
        <p>Geen historiek.</p>
    @endforelse

    @if ($expanded)
        <a href="{{ request()->fullUrlWithoutQuery('audit_history') }}">Minder historiek</a>
    @elseif ($allEvents->count() > $recent->count())
        <a href="{{ request()->fullUrlWithQuery(['audit_history' => 'all']) }}">Toon alle historiek</a>
    @endif
</x-chief::window>
