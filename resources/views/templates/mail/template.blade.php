@props([
    'preheader' => null,
    'logo' => null,
    'title' => null,
    'footer' => null,
])

<x-chief::mail.layout :title="$title">
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
                                @if ($logo)
                                    {{ $logo }}
                                @else
                                    @include('chief::templates.mail._partials.logo')
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td align="center" class="sm-px-24">
                                <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                        <td class="sm-px-24" style="border-radius: 4px; background-color: #ffffff; padding: 48px; text-align: left; font-size: 16px; line-height: 24px; color: #1f2937;">
                                            {{ $slot }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="height: 48px;"></td>
                                    </tr>

                                    <tr>
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
</x-chief::mail.layout>
