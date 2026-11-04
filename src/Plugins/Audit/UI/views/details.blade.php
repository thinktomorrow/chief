<div>
    <h2>{{ $model->model_snapshot['name'] }}</h2>
    @foreach ($model->context ?? [] as $key => $value)
        <p>{{ $key }}: {{ is_scalar($value) ? $value : json_encode($value) }}</p>
    @endforeach
    @foreach ($model->changes ?? [] as $path => $change)
        <section>
            <h3>{{ $path }}</h3>
            <div>
                Voor: {{ is_array($change['before']) ? ($change['before']['excerpt'] ?? 'Ontbreekt') : ($change['before'] === null ? 'null' : $change['before']) }}
            </div>
            <div>
                Na: {{ is_array($change['after']) ? ($change['after']['excerpt'] ?? 'Ontbreekt') : ($change['after'] === null ? 'null' : $change['after']) }}
            </div>
        </section>
    @endforeach
</div>
