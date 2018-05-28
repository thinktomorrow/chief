<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">

        /* CLIENT-SPECIFIC STYLES */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }

        /* RESET STYLES */
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: separate !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MOBILE STYLES */
        @media screen and (max-width:600px){
            h1 {
                font-size: 32px !important;
                line-height: 32px !important;
            }
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] { margin: 0 !important; }

        /* */
        .button{ border:1px solid #e8e8e8; background-color: #13c6a6; color: #FFFFFF; transition: 0.15s all ease-in-out;}
        .button:hover{ border:1px solid #15997A; background-color: #15997A; color: #FFFFFF; }
        ::selection{ background-color: #13c6a6; color: #FFFFFF; }
        ::-moz-selection{ background-color: #13c6a6; color: #FFFFFF; }
    </style>
</head>

<body style="background-color: #f5f5f5; margin: 0 !important; padding: 0 !important;">

<div style="display: none; font-size: 1px; color: #f5f5f5; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
    @yield('preheader')
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%" >
    <!-- LOGO -->
    <tr>
        <td bgcolor="#f5f5f5" align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                <tr>
                    <td align="center" valign="top" style="padding: 40px 10px 10px 10px;">
                        <a href="{{ URL('') }}" target="_blank">
                            <img alt="Logo" src="{{ asset('/chief-assets/back/img/logo.svg') }}" width="140" height="53" style="display: block; width: 140px; max-width: 140px; min-width: 140px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 18px;" border="0">
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ROUNDEND BLOCK TOP -->
    <tr>
        <td bgcolor="#f5f5f5" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                <tr>
                    <td bgcolor="#ffffff" align="center" valign="top" style="border-top: 1px solid #e8e8e8; border-left: 1px solid #e8e8e8; border-right: 1px solid #e8e8e8; padding: 20px 20px 20px 20px; border-radius: 6px 6px 0px 0px">
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- HERO -->
    <tr>
        <td bgcolor="#f5f5f5" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; border-left: 1px solid #e8e8e8; border-right: 1px solid #e8e8e8;" >
                <tr>
                    <td bgcolor="#ffffff" align="left" valign="top" style="padding: 25px 50px 25px 50px; color: #13c6a6; font-family: Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 400; letter-spacing: 1px; line-height: 24px;">
                        <h1 style="font-size: 24px; font-weight: 200; margin: 0;">@yield('title')</h1>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- COPY BLOCK -->
    <tr>
        <td bgcolor="#f5f5f5" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; border-left: 1px solid #e8e8e8; border-right: 1px solid #e8e8e8;" >
                @yield('content')
            </table>
        </td>
    </tr>
    <!-- ROUNDED BLOCK BOTTOM -->
    <tr>
        <td bgcolor="#f5f5f5" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                <tr>
                    <td bgcolor="#ffffff" align="center" valign="top" style="border-bottom: 1px solid #e8e8e8; border-left: 1px solid #e8e8e8; border-right: 1px solid #e8e8e8; padding: 20px 20px 20px 20px; border-radius: 0px 0px 6px 6px">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- FOOTER -->
    <tr>
        <td bgcolor="#f5f5f5" align="center" style="padding: 0px 10px 0px 10px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
                <tr>
                    <td bgcolor="#f5f5f5" align="left" style="padding: 30px 30px 30px 30px; color: #13c6a6; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px; text-align: center" >
                        <p style="margin: 0; padding:0;">&copy; {{ date('Y')}} • <a href="{{ url('') }}" target="_blank" style="color: #13c6a6;">{{ config('thinktomorrow.chief.name') }}</a></p>
                    </td>
                </tr>
                </td>
                </tr>
            </table>
</body>
</html>
