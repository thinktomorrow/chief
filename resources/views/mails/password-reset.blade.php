@php
    $title = 'Maak een nieuw wachtwoord aan voor ' . chiefSetting('app_name');
@endphp

<x-chief::mail.template :title="$title" preheader="Maak een nieuw Chief wachtwoord aan.">
    @include('chief::templates.mail._partials.title', ['content' => $title])

    <p style="margin: 0; margin-top: 12px; margin-bottom: 24px;">
        Je hebt zonet een nieuw wachtwoord aangevraagd.
        Klik op volgende link op een nieuw wachtwoord aan te maken:
    </p>

    @include('chief::templates.mail._partials.button', [
        'label' => 'Maak nieuw wachtwoord aan',
        'url' => $reset_url
    ])

    @include('chief::templates.mail._partials.divider')

    <p style="margin: 0; color: #4b5563; font-size: 14px; line-height: 22px;">
        Indien je zelf geen wachtwoord herstel hebt aangevraagd, kan je deze mail gerust negeren.
    </p>
</x-chief::mail.template>
