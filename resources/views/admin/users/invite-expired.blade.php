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
            <a
                href="mailto:{{ chiefSetting('contact_email') }}"
                title="Contacteer beheerder"
                class="btn btn-primary flex w-full justify-center"
            >
                Beheerder contacteren
            </a>

            <a
                href="{{ route('chief.back.login') }}"
                title="Ga naar login pagina"
                class="btn btn-grey flex w-full justify-center"
            >
                Naar login pagina
            </a>
        </div>
    </div>
</x-chief::solo.template>
