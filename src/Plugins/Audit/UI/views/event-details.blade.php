<x-chief::page.template title="Historiekdetails" container="md">
    <aside aria-label="Historiekdetails">
        <x-chief::window>
            <h1>{{ $event->type }}</h1>
            @if (! $event->recorded_at->equalTo($event->occurred_at))
                <p>Geregistreerd: {{ $event->recorded_at->setTimezone($timezone)->format('d/m/Y H:i') }}</p>
            @endif
            @foreach ($event->context as $key => $value)
                <p>{{ $key }}: {{ is_scalar($value) ? $value : json_encode($value) }}</p>
            @endforeach
        </x-chief::window>
    </aside>
</x-chief::page.template>
