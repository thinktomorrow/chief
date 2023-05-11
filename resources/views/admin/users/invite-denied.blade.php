<x-chief::solo.template title="Jouw uitnodiging is afgewezen">
    <div class="space-y-6">
        <div class="prose prose-spacing prose-dark">
            <p>Ok. Je hebt je uitnodiging tot {{ chiefSetting('app_name') }} afgewezen.</p>
            <p>Mocht je toch opnieuw toegang wensen, kan je je wenden tot de beheerder ({{ chiefSetting('contact_name') }}).</p>
        </div>

        <div class="space-y-3">
            <a
                href="mailto:{{ chiefSetting('contact_email') }}"
                title="Contacteer beheerder"
                class="flex justify-center w-full btn btn-primary"
            > Beheerder contacteren </a>

            <a
                href="{{ route('chief.back.login') }}"
                title="Naar login pagina"
                class="flex justify-center w-full btn btn-grey"
            > Naar login pagina </a>
        </div>
    </div>
</x-chief::solo.template>
