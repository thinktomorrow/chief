<x-chief::solo.template title="Uitnodiging niet langer geldig">
    <div class="space-y-6">
        <div class="prose prose-dark prose-spacing">
            <p>Deze link is jammer genoeg ongeldig. Mogelijk is de reden een van de volgende:</p>

            <ul>
                <li>De uitnodiging is reeds aanvaard.</li>
                <li>
                    De uitnodiging is vervallen. Binnen 3 dagen van ontvangst dient een uitnodiging te worden aanvaard.
                </li>
                <li>
                    Zorg ervoor dat de volledige uitnodigingslink wordt gebruikt. Van zodra een deeltje ontbreekt, is
                    deze niet geldig.
                </li>
                <li>De uitnodiging is teruggetrokken door de beheerder ({{ chiefSetting('contact_name') }}).</li>
            </ul>
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
