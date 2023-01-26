<x-chief::template title="Settings">
    <x-slot name="hero">
        <x-chief::template.hero title="Settings" class="max-w-3xl">
            <button form="updateForm" type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
        </x-chief::template.hero>
    </x-slot>

    <x-chief::template.grid class="max-w-3xl">
        <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form" class="card">
            @csrf
            @method('put')

            <div class="space-y-6">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>
        </form>
    </x-chief::template.grid>
</x-chief::template>
