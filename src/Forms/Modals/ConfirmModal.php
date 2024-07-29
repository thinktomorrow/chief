<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Modals;

class ConfirmModal
{
    public static function make(string $id = 'confirm-modal'): Modal
    {
        return Modal::make($id)
            ->button('Ja, ga door')
            ->title('Bevestigen')
            ->content('Ben je zeker dat je deze actie wil uitvoeren?');
    }
}
