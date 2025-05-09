<x-chief::solo.template title="Jouw uitnodiging is afgewezen">
    <div class="space-y-6">
        <div class="prose prose-dark prose-spacing">
            <p>Ok. Je hebt je uitnodiging tot {{ chiefSetting('app_name') }} afgewezen.</p>
            <p>
                Mocht je toch opnieuw toegang wensen, kan je je wenden tot de beheerder
                ({{ chiefSetting('contact_name') }}).
            </p>
        </div>

        <div class="space-y-3">
            <x-chief::button
                href="mailto:{{ chiefSetting('contact_email') }}"
                title="Contacteer beheerder"
                size="lg"
                variant="blue"
                class="flex w-full justify-center"
            >
                Contacteer beheerder
            </x-chief::button>

            <x-chief::button
                href="{{ route('chief.back.login') }}"
                title="Naar login pagina"
                size="lg"
                class="flex w-full justify-center"
            >
                Naar login pagina
            </x-chief::button>
        </div>
    </div>
</x-chief::solo.template>
