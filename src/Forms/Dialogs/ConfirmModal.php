<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Dialogs;

class ConfirmModal
{
    public static function make(string $id = 'confirm-modal'): Dialog
    {
        return Dialog::make($id)
            ->asModal()
            ->button('Ja, ga door')
            ->title('Bevestigen')
            ->content('Ben je zeker dat je deze actie wil uitvoeren?');
    }
}
