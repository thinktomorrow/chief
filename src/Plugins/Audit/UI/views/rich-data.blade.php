<div>
    @foreach ($pieces as $piece)
        <section>
            <h2>{{ $piece->type }}</h2>
            @if ($piece->status !== 'available')
                <p>{{ match ($piece->status) { 'removed' => 'Verwijderd door bewaring', 'unavailable' => 'Referentie onbeschikbaar', 'limit', 'error' => 'Niet beschikbaar door limiet of vastlegfout', default => 'Niet vastgelegd' } }}</p>
            @elseif ($piece->type === 'text')
                <pre>{{ $piece->content }}</pre>
            @elseif (in_array($piece->type, ['html', 'mailpreview'], true))
                <iframe
                    title="Historische HTML"
                    sandbox=""
                    referrerpolicy="no-referrer"
                    src="{{ route('chief.audit.rich-html', [$eventId, $piece->getKey()]) }}"
                ></iframe>
            @elseif ($piece->type === 'reference')
                <a
                    href="{{ route('chief.audit.rich-reference', [$eventId, $piece->getKey()]) }}"
                    >{{ $piece->metadata['name'] }}</a
                >
            @endif
            @if ($piece->status === 'available' && $piece->type !== 'reference')
                @foreach ($piece->metadata ?? [] as $key => $value)
                    <p>{{ $key }}: {{ is_scalar($value) ? $value : json_encode($value) }}</p>
                @endforeach
            @endif
        </section>
    @endforeach
</div>
