<div>
    <h2>{{ $history->presentation($event)->label }}</h2>
    @if ($missingMailPreview)
        <p>Mailpreview: Nooit vastgelegd</p>
    @endif
    @if (! $event->recorded_at->equalTo($event->occurred_at))
        <p>Geregistreerd: {{ $event->recorded_at->setTimezone($timezone)->format('d/m/Y H:i') }}</p>
    @endif
    @foreach ($event->context as $key => $value)
        <p>{{ $key === 'legacy' ? 'Brongegevens' : $key }}: {{ is_scalar($value) ? $value : json_encode($value) }}</p>
    @endforeach
</div>
