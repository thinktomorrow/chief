@extends('chief::back._layouts.mail')

@section('preheader')
    Uitnodiging tot het beheer van {{ chiefSetting('app_name') }}.
@endsection

@section('title')
    Uitnodiging tot {{ chiefSetting('app_name') }}.
@endsection

@section('content')
    <tr>
        <td bgcolor="#ffffff" align="left" style="padding: 0px 50px 25px 50px; color: #808080; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;" >
            <p style="margin: 0; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 21px;">
                Je bent uitgenodigd door jouw collega {{ $inviter->firstname }}. Hierbij krijg je toegang tot het beheer van {{ chiefSetting('app_name') }}. Opgelet deze uitnodiging is slechts 3 dagen geldig.<br><br>
                Klik op volgende link op de uitnodiging te aanvaarden:
            </p>

            <p style="margin: 40px 0 20px 0;">
                @include('chief::back.mails._button',[
                'url' => $accept_url,
                'label' => 'Aanvaard uitnodiging',
            ])
            </p>

            <p style="margin: 0; font-family: Helvetica, Arial, sans-serif; font-size: 10px; font-weight: 100; line-height: 12px;">
                Indien je vindt dat dit een vergissing is of geen toegang wenst, kan je deze mail negeren. Eventueel kan je de <a href="{{ $deny_url }}">uitnodiging expliciet weigeren</a>.
            </p>

        </td>
    </tr>

@endsection
