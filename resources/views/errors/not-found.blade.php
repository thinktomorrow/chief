<x-chief::solo.template title="Pagina niet gevonden">
    <div class="space-y-6">
        <div class="prose prose-dark prose-spacing">
            <p>
                De opgevraagde pagina bestaat niet (meer) of werd verwijderd.
                <br />
                Ga terug naar het dashboard om verder te werken.
            </p>
        </div>

        <div class="space-y-3">
            <x-chief::button
                href="{{ route('chief.back.dashboard') }}"
                title="Naar het dashboard"
                size="lg"
                variant="blue"
                class="flex w-full justify-center"
            >
                Naar het dashboard
            </x-chief::button>
        </div>
    </div>
</x-chief::solo.template>
