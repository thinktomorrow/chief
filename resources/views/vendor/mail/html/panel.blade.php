<table class="border border-grey-100 rounded" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        {{ Illuminate\Mail\Markdown::parse($slot) }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
