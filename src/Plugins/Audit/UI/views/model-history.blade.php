<x-chief::window title="Historiek">
    @forelse ($events as $event)
        @php ($presentation = $history->presentation($event))
        <article
            class="relative flex gap-2 {{ $loop->last ? 'pb-0' : 'pb-6' }} {{ $history->priority($event) === 'secondary' ? 'text-grey-500' : 'text-grey-900' }}"
        >

            <span aria-hidden="true" class="bg-grey-100 absolute"
                  style="top: 1rem; left: 1rem; width: 1px; height: 100%; margin-left: -1px"></span>

            <span
                class="relative flex size-8 shrink-0 items-center justify-center rounded-full {{ match ($presentation->color) { 'blue' => 'bg-blue-50 text-blue-500', 'green' => 'bg-green-50 text-green-500', 'red' => 'bg-red-50 text-red-500', 'orange' => 'bg-orange-50 text-orange-500', 'primary' => 'bg-primary-50 text-primary-500', default => 'bg-grey-100 text-grey-500' } }}">
                <x-dynamic-component
                    :component="view()->exists('chief::components.icon.' . $presentation->icon) ? 'chief::icon.' . $presentation->icon : 'chief::icon.information-circle'"
                    class="size-5"
                />
            </span>
            <div class="space-y-1.5 pt-1 text-sm">

                <p>{{ $event->summary ?: $presentation->label }}</p>
                @if ($event->model_snapshot && (! $event->summary || $event->model_type !== $modelType))
                    <p class="text-grey-500">{{ $event->model_snapshot['name'] }}</p>
                @endif
                @if ($event->model_type !== $modelType)
                    @foreach (config('chief-audit.model_context', []) as $key => $label)
                        @if (is_scalar($event->context[$key] ?? null) && (string) $event->context[$key] !== '')
                            <p class="text-grey-500">{{ $label }}: {{ $event->context[$key] }}</p>
                        @endif
                    @endforeach
                @endif
                @if ($event->actor_type === 'admin')
                    <p class="text-grey-500">door {{ $event->actor_snapshot['name'] }}</p>
                @endif
                @if ($event->outcome)
                    <p class="text-grey-500">{{ $event->outcome }}</p>
                @endif
                @foreach ($event->models as $model)
                    @foreach ($model->changes ?? [] as $path => $change)
                        <div class="text-grey-500">
                            <span
                                class="font-medium"
                            >{{ config('chief-audit.field_labels.' . $path, str_replace('_', ' ', $path)) }}</span
                            >
                            <span
                                class="line-through"
                            >{{ is_array($change['before']) ? ($change['before']['excerpt'] ?? '-') : ($change['before'] ?? '-') }}</span
                            >
                            <span>→</span>
                            <span
                            >{{ is_array($change['after']) ? ($change['after']['excerpt'] ?? '-') : ($change['after'] ?? '-') }}</span
                            >
                        </div>
                    @endforeach
                @endforeach
                @if ($event->visible_rich_data)
                    <a class="btn cursor-pointer btn-xs btn-outline-white"
                       href="{{ route('chief.audit.rich-data', $event->getKey()) }}">Bekijk inhoud</a>
                @endif
                <time
                    class="text-grey-500 block"
                    datetime="{{ $event->occurred_at->toIso8601String() }}"
                >{{ $event->occurred_at->setTimezone($history->timezone())->format('d/m/y H:i') }}</time
                >
            </div>
        </article>
    @empty
        <p>Geen historiek.</p>
    @endforelse

    @if ($expanded || $hasMore)
        <div class="mt-4">
            <x-chief::button
                href="{{ $expanded ? request()->fullUrlWithoutQuery('audit_history') : request()->fullUrlWithQuery(['audit_history' => 'all']) }}"
                variant="outline-white"
                size="sm"
            >
                {{ $expanded ? 'Minder historiek' : 'Toon alle historiek' }}
            </x-chief::button>
        </div>
    @endif
</x-chief::window>
