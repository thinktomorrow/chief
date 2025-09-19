<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\Action;

class DeleteRowAction extends Action
{
    public static function makeDefault(string $resourceKey): static
    {
        return static::make('delete-row')
            ->label('Verwijder')
            ->variant('red')
            ->prependIcon('<x-chief::icon.delete />')
            ->dialog(
                Dialog::make('deleteModal')
                    ->title('Verwijder itemmm')
                    ->content('
                        <p>
                            Weet je zeker dat je dit item wilt verwijderen? Je kunt dit item later niet meer terughalen.
                        </p>
                    ')
                    ->button('Verwijderen')
                    ->buttonVariant('red')
            )
            ->effect(function ($formData, $data) {
                try {
                    $modelReference = ModelReference::fromString($data['item']);

                    $modelReference->instance()->delete();
                } catch (\Exception $e) {
                    report($e);

                    return false;
                }

                return true;
            })
            ->notifyOnSuccess('Item is verwijderd')->notifyOnFailure('Er is iets misgegaan bij het verwijderen.');
    }
}
