<x-chief::page.template title="Historiek" container="md">
    <div x-data class="space-y-6">
        <x-chief::window>
            <h1>Historiek</h1>
            <p>{{ $events->total() }} resultaten</p>
            <form method="get">
                <input name="search" value="{{ request('search') }}" aria-label="Zoeken" />
                <input name="from" type="date" value="{{ request('from') }}" aria-label="Vanaf" />
                <input name="to" type="date" value="{{ request('to') }}" aria-label="Tot" />
                <input name="model_id" value="{{ request('model_id') }}" list="audit-models" aria-label="Model-ID" />
                <datalist id="audit-models">
                    @foreach ($options['models'] as $modelId => $name)
                        <option value="{{ $modelId }}">{{ $name }}</option>
                    @endforeach
                </datalist>
                @foreach (['categories' => ['category', 'Categorie'], 'types' => ['type', 'Typesleutel'], 'outcomes' => ['outcome', 'Uitkomst'], 'model_types' => ['model_type', 'Modeltype'], 'actors' => ['actor', 'Actor']] as $option => [$field, $label])
                    <select name="{{ $field }}" aria-label="{{ $label }}">
                        <option value="">Alle {{ strtolower($label) }}</option>
                        @foreach ($options[$option] as $key => $value)
                            @php ($choice = in_array($option, ['actors'], true) ? $key : $value)
                            <option value="{{ $choice }}" @selected (request($field) === (string) $choice)>
                                {{ match ($option) { 'categories' => $history->categoryLabel($value), 'types' => $history->typeLabel($value), default => $value } }}
                            </option>
                        @endforeach
                    </select>
                @endforeach
                <select name="actor_type" aria-label="Actorsoort">
                    <option value="">Alle actorsoorten</option>
                    @foreach ($options['actor_types'] as $actorType)
                        <option value="{{ $actorType }}" @selected (request('actor_type') === $actorType)>
                            {{ $actorType }}
                        </option>
                    @endforeach
                </select>
                <button type="submit">Zoeken</button>
            </form>

            @if (! request()->boolean('show_all') && ! $activeFilters)
                <a href="{{ route('chief.audit.index', array_merge(request()->query(), ['show_all' => 1])) }}"
                    >Alles tonen</a
                >
            @endif

            @forelse ($events->getCollection()->groupBy(fn ($event) => $event->occurred_at->setTimezone($timezone)->toDateString()) as $day => $dayEvents)
                <section>
                    <h2>{{ $dayEvents->first()->occurred_at->setTimezone($timezone)->format('d/m/Y') }}</h2>
                    @foreach ($dayEvents as $event)
                        @include ('chief-audit::index-event', ['last' => $loop->last])
                    @endforeach
                </section>
            @empty
                <p>Geen historiek.</p>
            @endforelse

            {{ $events->links() }}
        </x-chief::window>
        <livewire:chief-audit::details />
    </div>
</x-chief::page.template>
