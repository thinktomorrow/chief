<x-chief::page.template title="Settings">
    <x-slot name="hero">
        <x-chief::page.hero title="Settings" class="max-w-3xl">
            <button form="updateForm" type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form" class="card">
            @csrf
            @method('put')

            <div class="space-y-6">
                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
