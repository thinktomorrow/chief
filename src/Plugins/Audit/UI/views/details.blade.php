<x-chief::page.template title="Wijzigingsdetails" container="md">
    <aside aria-label="Wijzigingsdetails">
        <x-chief::window>
            <h1>{{ $model->model_snapshot['name'] }}</h1>
            @foreach ($model->context ?? [] as $key => $value)
                <p>{{ $key }}: {{ is_scalar($value) ? $value : json_encode($value) }}</p>
            @endforeach
            @foreach ($model->changes ?? [] as $path => $change)
                <section>
                    <h2>{{ $path }}</h2>
                    <div>
                        Voor: {{ is_array($change['before']) ? ($change['before']['excerpt'] ?? 'Ontbreekt') : ($change['before'] === null ? 'null' : $change['before']) }}
                    </div>
                    <div>
                        Na: {{ is_array($change['after']) ? ($change['after']['excerpt'] ?? 'Ontbreekt') : ($change['after'] === null ? 'null' : $change['after']) }}
                    </div>
                </section>
            @endforeach
        </x-chief::window>
    </aside>
</x-chief::page.template>
