<x-chief::window title="Historiek">
    @forelse ($events->groupBy(fn ($event) => $event->occurred_at->setTimezone($history->timezone())->toDateString()) as $day => $dayEvents)
        <section class="border-grey-100 space-y-2 border-b py-3">
            <h3 class="text-grey-700 text-sm font-semibold">
                {{ $dayEvents->first()->occurred_at->setTimezone($history->timezone())->format('d/m/Y') }}
            </h3>
            @foreach ($dayEvents as $event)
                <article
                    class="text-sm {{ $history->priority($event) === 'secondary' ? 'text-grey-500' : 'text-grey-900' }}"
                >
                    @include ('chief-audit::event-line', ['timezone' => $history->timezone(), 'showOutcome' => true])
                </article>
            @endforeach
        </section>
    @empty
        <p>Geen historiek.</p>
    @endforelse

    @if ($expanded)
        <a href="{{ request()->fullUrlWithoutQuery('audit_history') }}">Minder historiek</a>
    @elseif ($hasMore)
        <a href="{{ request()->fullUrlWithQuery(['audit_history' => 'all']) }}">Toon alle historiek</a>
    @endif
</x-chief::window>
