@php ($presentation = $history->presentation($event))
<time datetime="{{ $event->occurred_at->toIso8601String() }}">
    {{ $event->occurred_at->setTimezone($timezone)->format(($compact ?? false) ? 'd/m H:i' : 'H:i') }}
</time>
@if ($showIcon ?? false)
    <span
        class="{{ match ($presentation->color) { 'blue' => 'text-blue-500', 'green' => 'text-green-500', 'red' => 'text-red-500', 'orange' => 'text-orange-500', 'primary' => 'text-primary-500', default => 'text-grey-500' } }}"
        aria-label="Icoon"
    >
        <x-dynamic-component
            :component="view()->exists('chief::components.icon.' . $presentation->icon) ? 'chief::icon.' . $presentation->icon : 'chief::icon.information-circle'"
            class="inline size-5"
        />
    </span>
@endif
<span>{{ $event->actor_snapshot['name'] ?? 'Actor' }}</span>
<span>{{ $presentation->label }}</span>
@if ($event->summary)
    <span>{{ $event->summary }}</span>
@endif
@if (($showOutcome ?? false) && $event->outcome)
    <span>{{ $event->outcome }}</span>
@endif
@if ($event->model_snapshot)
    <span>{{ $event->model_snapshot['name'] ?? '' }}</span>
@endif
