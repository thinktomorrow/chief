<x-chief::page.template title="Historiekdetails" container="md">
    <aside
        aria-label="Historiekdetails"
        class="fixed inset-y-0 right-0 z-50 w-full max-w-md overflow-y-auto bg-white p-6 shadow-xl"
    >
        <x-chief::window>
            <a href="{{ route('chief.audit.index') }}">Terug naar historiek</a>
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
