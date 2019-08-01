@extends('chief::back._layouts.mail')

@section('preheader')
    Maak een nieuw Chief wachtwoord aan.
@endsection

@section('title')
    Maak een nieuw Chief wachtwoord aan voor {{ chiefSetting('client_app_name') }}.
@endsection

@section('content')
    <tr>
        <td bgcolor="#ffffff" align="left" style="padding: 0px 50px 25px 50px; color: #5C4456; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;" >
            <p style="margin: 0; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 21px;">
                Je hebt zonet een nieuw wachtwoord aangevraagd.<br>
                Klik op volgende link op een nieuw wachtwoord aan te maken:
            </p>

            <p style="margin: 40px 0 20px 0;">
                @include('chief::back.mails._button',[
                    'url' => $reset_url,
                    'label' => 'Maak nieuw wachtwoord aan',
                ])
            </p>

            <p style="margin: 0; font-family: Helvetica, Arial, sans-serif; font-size: 10px; font-weight: 100; line-height: 12px;">
                Indien je zelf geen wachtwoord herstel hebt aangevraagd, kan je deze mail gerust negeren.
            </p>

        </td>
    </tr>

@endsection