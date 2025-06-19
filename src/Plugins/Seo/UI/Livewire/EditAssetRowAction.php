<?php

namespace Thinktomorrow\Chief\Plugins\Seo\UI\Livewire;

use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\RowAction;

class EditAssetRowAction extends RowAction
{
    public static function makeDefault(): static
    {
        return static::make('edit-asset')
            ->label('Bestandsnaam en alt-tekst bewerken')
            ->effect(function ($formData, $data, $action, $component) {
                $modelReference = ModelReference::fromString($data['item']);

                $component->dispatch('open-seo-asset', [
                    'previewfile' => PreviewFile::fromAsset($modelReference->instance()),
                ]);
            });
    }
}
