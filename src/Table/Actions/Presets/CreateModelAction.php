<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Table\Actions\Action;

class CreateModelAction extends Action
{
    public static function makeDefault(string $resourceKey, array $instanceAttributes = []): static
    {
        $resource = app(Registry::class)->resource($resourceKey);

        return static::make('create')
            ->label(ucfirst($resource->getLabel()).' toevoegen')
            ->prependIcon('<x-chief::icon.plus-sign />')
            ->effect(function ($formData, $data, $action, $component) use ($resource, $instanceAttributes) {
                $component->dispatch('open-create-model', [
                    'modelClass' => $resource::modelClassName(),
                    'locales' => ChiefSites::locales(),
                    'instanceAttributes' => $instanceAttributes,
                    'redirectAfterSave' => false,
                ]);
            });
    }
}
