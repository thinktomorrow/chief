<x-chief::page.template title="Historische inhoud" container="md">
    <aside aria-label="Historische inhoud" class="fixed inset-y-0 right-0 z-50 w-full max-w-md overflow-y-auto bg-white p-6 shadow-xl">
        <x-chief::window>
            <a href="{{ route('chief.audit.index') }}">Terug naar historiek</a>
            <h1>Historische inhoud</h1>
            @foreach ($pieces as $piece)
                <section>
                    <h2>{{ $piece->type }}</h2>
                    @if ($piece->status !== 'available')
                        <p>{{ match ($piece->status) { 'removed' => 'Verwijderd door bewaring', 'unavailable' => 'Referentie onbeschikbaar', default => 'Niet vastgelegd' } }}</p>
                    @elseif ($piece->type === 'text')
                        <pre>{{ $piece->content }}</pre>
                    @elseif (in_array($piece->type, ['html', 'mailpreview'], true))
                        <iframe title="Historische HTML" sandbox="" referrerpolicy="no-referrer" src="{{ route('chief.audit.rich-html', [$eventId, $piece->getKey()]) }}"></iframe>
                    @elseif ($piece->type === 'reference')
                        <a href="{{ route('chief.audit.rich-reference', [$eventId, $piece->getKey()]) }}">{{ $piece->metadata['name'] }}</a>
                    @endif
                    @if ($piece->status === 'available' && $piece->type !== 'reference')
                        @foreach ($piece->metadata ?? [] as $key => $value)
                            <p>{{ $key }}: {{ is_scalar($value) ? $value : json_encode($value) }}</p>
                        @endforeach
                    @endif
                </section>
            @endforeach
        </x-chief::window>
    </aside>
</x-chief::page.template>
