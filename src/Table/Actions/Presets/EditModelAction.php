<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Table\Actions\Action;

class EditModelAction extends Action
{
    public static function makeDefault(string $resourceKey): static
    {
        return static::make('edit')
            ->link(function ($model) use ($resourceKey) {
                return '/admin/'.$resourceKey.'/'.$model->getKey().'/edit';
            })
            ->iconEdit();
    }

    public static function makeDefaultAsDialog(string $resourceKey): static
    {
        $resource = app(Registry::class)->resource($resourceKey);

        return static::make('edit-dialog')
            ->label(ucfirst($resource->getLabel()).' bewerken')
            ->iconEdit()
            ->effect(function ($formData, $data, $action, $component) {
                $modelReference = ModelReference::fromString($data['item']);

                $component->dispatch('open-edit-model', [
                    'modelReference' => $modelReference->get(),
                    'locales' => ChiefSites::locales(),
                ]);
            });
    }
}
