@props([
    'title' => null,
    'preheader' => null,
    'footer' => null,
])

<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <!--[if mso]>
    <noscript>
        <xml>
        <o:OfficeDocumentSettings xmlns:o="urn:schemas-microsoft-com:office:office">
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <style>
        td,th,div,p,a,h1,h2,h3,h4,h5,h6 {font-family: "Segoe UI", sans-serif; mso-line-height-rule: exactly;}
    </style>
    <![endif]-->
    <title>{{ $title }}</title>
    <style>
        .hover-bg-blue-600:hover {
            background-color: #6366f1 !important;
        }
        .hover-underline:hover {
            text-decoration: underline !important;
        }
        .hover-no-underline:hover {
            text-decoration: none !important;
        }
        @media (max-width: 600px) {
            .sm-w-full {
                width: 100% !important;
            }
            .sm-py-32 {
                padding-top: 32px !important;
                padding-bottom: 32px !important;
            }
            .sm-px-24 {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }
            .sm-leading-32 {
                line-height: 32px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; width: 100%; padding: 0; word-break: break-word; -webkit-font-smoothing: antialiased; background-color: #f3f4f6;">
    {{-- Preheader --}}
    @if ($preheader)
        <div style="display: none;">{{ $preheader }}</div>
    @endif

    <div role="article" aria-roledescription="email" aria-label="{{ $title }}" lang="nl">
        <table style="width: 100%; font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="background-color: #f3f4f6;">
                    <table class="sm-w-full" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td class="sm-py-32 sm-px-24" style="padding: 48px; text-align: center;">
                                @include('chief::templates.mail._partials.logo')
                            </td>
                        </tr>

                        <tr>
                            <td align="center" class="sm-px-24">
                                <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                        {{-- Main content container --}}
                                        <td class="sm-px-24" style="border-radius: 4px; background-color: #ffffff; padding: 48px; text-align: left; font-size: 16px; line-height: 24px; color: #1f2937;">
                                            {{ $slot }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="height: 48px;"></td>
                                    </tr>

                                    <tr>
                                        {{-- Footer --}}
                                        <td style="padding-left: 24px; padding-right: 24px; text-align: center; font-size: 12px; color: #4b5563;">
                                            @if ($footer)
                                                {{ $footer }}
                                            @else
                                                <p style="margin: 0; margin-bottom: 4px;">
                                                    Powered by Chief
                                                </p>

                                                <p style="margin: 0; font-style: italic;">
                                                    Made with ♡ by
                                                    @include('chief::templates.mail._partials.link', [
                                                        'label' => 'Think Tomorrow',
                                                        'url' => 'https://thinktomorrow.be'
                                                    ])
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
