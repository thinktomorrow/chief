<x-chief::page.template title="Historiek" container="md">
    <x-chief::window>
        <h1>Historiek</h1>
        <p>{{ $events->total() }} resultaten</p>
        <form method="get">
            <input name="search" value="{{ request('search') }}" aria-label="Zoeken" />
            <input name="from" type="date" value="{{ request('from') }}" aria-label="Vanaf" />
            <input name="to" type="date" value="{{ request('to') }}" aria-label="Tot" />
            <input name="model_id" value="{{ request('model_id') }}" list="audit-models" aria-label="Model-ID" />
            @foreach ($options['filters'] as $key => $filter)
                <input
                    name="filter[{{ $key }}]"
                    value="{{ request('filter.' . $key) }}"
                    aria-label="{{ $filter->label() }}"
                />
            @endforeach
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
                            {{ $option === 'categories' ? $history->categoryLabel($value) : $value }}
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
                    @php ($presentation = $history->presentation($event))
                    <article
                        class="space-y-1 border-b border-grey-100 py-3 {{ $history->priority($event) === 'secondary' ? 'text-grey-500' : 'text-grey-900' }}"
                    >
                        <time
                            datetime="{{ $event->occurred_at->toIso8601String() }}"
                            >{{ $event->occurred_at->setTimezone($timezone)->format('H:i') }}</time
                        >
                        @php ($icon = $presentation->icon())
                        <span
                            class="{{ match ($presentation->color()) { 'blue' => 'text-blue-500', 'green' => 'text-green-500', 'red' => 'text-red-500', 'orange' => 'text-orange-500', 'primary' => 'text-primary-500', default => 'text-grey-500' } }}"
                            aria-label="Icoon"
                        >
                            <x-dynamic-component
                                :component="view()->exists('chief::components.icon.' . $icon) ? 'chief::icon.' . $icon : 'chief::icon.information-circle'"
                                class="inline size-5"
                            />
                        </span>
                        <span>{{ $event->actor_snapshot['name'] }}</span>
                        <span>{{ $presentation->label() }}</span>
                        @if ($event->summary)
                            <span>{{ $event->summary }}</span>
                        @endif
                        @if ($event->type === 'legacy.spatie' && $event->context)
                            <pre
                                >{{ json_encode($event->context['legacy'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE) }}</pre
                            >
                        @endif
                        @if ($event->outcome)
                            <span>{{ $event->outcome }}</span>
                        @endif
                        @if ($event->model_snapshot)
                            <span>{{ $event->model_snapshot['name'] }}</span>
                        @endif
                        @if ($event->context || ! $event->recorded_at->equalTo($event->occurred_at))
                            <a href="{{ route('chief.audit.event-details', $event->getKey()) }}">Details</a>
                        @endif
                        @foreach ($event->models as $model)
                            @if ($model->changes || $model->context)
                                <a href="{{ route('chief.audit.details', [$event->getKey(), $model->getKey()]) }}"
                                    >{{ $model->changes ? 'Wijzigingen' : 'Details' }}: {{ $model->model_snapshot['name'] }}</a
                                >
                            @elseif ($model->getKey() !== $event->models->first()?->getKey())
                                <span>{{ $model->model_snapshot['name'] }}</span>
                            @endif
                        @endforeach
                    </article>
                @endforeach
            </section>
        @empty
            <p>Geen historiek.</p>
        @endforelse

        {{ $events->links() }}
    </x-chief::window>
</x-chief::page.template>
