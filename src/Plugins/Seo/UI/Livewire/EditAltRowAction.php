<?php

namespace Thinktomorrow\Chief\Plugins\Seo\UI\Livewire;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\RowAction;

class EditAltRowAction extends RowAction
{
    public static function makeDefault(): static
    {
        return static::make('edit-alt')
            ->label('Alt-tekst bewerken')
            ->effect(function ($formData, $data, $action, $component) {
                $modelReference = ModelReference::fromString($data['item']);

                $component->dispatch('open-edit-alt', [
                    'modelReference' => $modelReference->get(),
                ]);
            });

        //        return static::make('edit-alt')
        //            ->label('Alt-tekst bewerken')
        //            ->dialog(
        //                Dialog::make('altModal')
        //                    ->asDrawer()
        //                    ->title('Bewerk de alt-tekst')
        //                    ->form([
        //                        Text::make('alt')
        //                            ->locales()
        //                            ->label('Alt tekst'),
        //                    ])
        //                    ->button('Aanpassen')
        //            )->effect(function ($formData, $data) {
        //
        //                try {
        //
        //                    // Extract the asset ID from the data item structured as <class@id>
        //                    $assetId = substr($data['item'], strpos($data['item'], '@') + 1);
        //
        //                    app(FileApplication::class)->updateAssetData($assetId, $formData);
        //
        //                    return true;
        //                } catch (\Exception $e) {
        //                    report($e);
        //
        //                    return false;
        //                }
        //            })
        //            ->notifyOnSuccess('Alt-tekst is aangepast!')
        //            ->notifyOnFailure('Er is iets misgegaan bij het aanpassen van de alt-tekst.');
    }
}
