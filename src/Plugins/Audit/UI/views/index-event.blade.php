@php ($presentation = $history->presentation($event))
<article
    class="relative flex gap-2 {{ $last ? 'pb-0' : 'pb-6' }} {{ $history->priority($event) === 'secondary' ? 'text-grey-500' : 'text-grey-900' }}"
>
    <span
        aria-hidden="true"
        class="bg-grey-100 absolute"
        style="top: 1rem; left: 1rem; width: 1px; height: 100%; margin-left: -1px"
    ></span>

    <span
        class="relative flex size-8 shrink-0 items-center justify-center rounded-full {{ match ($presentation->color) { 'blue' => 'bg-blue-50 text-blue-500', 'green' => 'bg-green-50 text-green-500', 'red' => 'bg-red-50 text-red-500', 'orange' => 'bg-orange-50 text-orange-500', 'primary' => 'bg-primary-50 text-primary-500', default => 'bg-grey-100 text-grey-500' } }}"
    >
        <x-dynamic-component
            :component="view()->exists('chief::components.icon.' . $presentation->icon) ? 'chief::icon.' . $presentation->icon : 'chief::icon.information-circle'"
            class="size-5"
        />
    </span>

    <div class="space-y-1.5 pt-1 text-sm">
        <p>{{ $event->summary ?: $presentation->label }}</p>
        @if ($event->summary && $event->summary !== $presentation->label)
            <p class="text-grey-500">{{ $presentation->label }}</p>
        @endif

        @foreach ($event->models as $model)
            <p class="text-grey-500">{{ $model->model_snapshot['name'] }}</p>
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
            @if ($model->changes || $model->context)
                <button
                    type="button"
                    x-on:click="$dispatch('audit:show-detail', { kind: 'model', eventId: '{{ $event->getKey() }}', modelId: '{{ $model->getKey() }}' })"
                >
                    {{ $model->changes ? 'Wijzigingen' : 'Details' }}: {{ $model->model_snapshot['name'] }}
                </button>
            @endif
        @endforeach

        @foreach (config('chief-audit.model_context', []) as $key => $label)
            @if (is_scalar($event->context[$key] ?? null) && (string) $event->context[$key] !== '')
                <p class="text-grey-500">{{ $label }}: {{ $event->context[$key] }}</p>
            @endif
        @endforeach

        <p class="text-grey-500">door {{ $event->actor_snapshot['name'] ?? 'Actor' }}</p>
        @if ($event->outcome)
            <p class="text-grey-500">{{ $event->outcome }}</p>
        @endif
        @if ($event->context || ! $event->recorded_at->equalTo($event->occurred_at) || $history->missingMailPreview($event))
            <button
                type="button"
                x-on:click="$dispatch('audit:show-detail', { kind: 'event', eventId: '{{ $event->getKey() }}' })"
            >
                Details
            </button>
        @endif
        @if ($event->visible_rich_data)
            <button
                type="button"
                x-on:click="$dispatch('audit:show-detail', { kind: 'rich', eventId: '{{ $event->getKey() }}' })"
            >
                Historische inhoud
            </button>
        @endif
        <time
            class="text-grey-500 block"
            datetime="{{ $event->occurred_at->toIso8601String() }}"
            >{{ $event->occurred_at->setTimezone($timezone)->format('d/m/y H:i') }}</time
        >
    </div>
</article>
