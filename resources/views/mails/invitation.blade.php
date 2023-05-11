@php
    $title = 'Uitnodiging tot ' . chiefSetting('app_name');
@endphp

<x-chief::mail.template
    :title="$title"
    preheader="Uitnodiging tot het beheer van {{ chiefSetting('app_name') }}."
>
    @include('chief::templates.mail._partials.title', ['content' => $title])

    <p style="margin: 0; margin-top: 12px; margin-bottom: 24px;">
        Je bent uitgenodigd door jouw collega {{ $inviter->firstname }}.
        Hierbij krijg je toegang tot het beheer van {{ chiefSetting('app_name') }}.
        Opgelet deze uitnodiging is slechts 3 dagen geldig.
        Klik op volgende link op de uitnodiging te aanvaarden:
    </p>

    @include('chief::templates.mail._partials.button', [
        'label' => 'Aanvaard uitnodiging',
        'url' => $accept_url
    ])

    @include('chief::templates.mail._partials.divider')

    <p style="margin: 0; color: #4b5563; font-size: 14px; line-height: 22px;">
        Indien je vindt dat dit een vergissing is of geen toegang wenst, kan je deze mail negeren.
        Eventueel kan je de
        @include('chief::templates.mail._partials.link', [
            'label' => 'uitnodiging expliciet weigeren',
            'url' => $deny_url,
        ]).
    </p>
</x-chief::mail.template>
