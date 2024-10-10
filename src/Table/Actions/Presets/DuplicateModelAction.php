<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\ManagedModels\Actions\Duplicate\DuplicatePage;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\Action;

class DuplicateModelAction extends Action
{
    public static function makeDefault(string $resourceKey): static
    {
        $resource = app(Registry::class)->resource($resourceKey);
        $manager = app(Registry::class)->manager($resourceKey);

        return static::make('duplicate')
            ->label("Kopieer")
        //            ->icon('<x-chief::icon.quill-write />')
        ->effect(function ($formData, $data) use ($resource, $manager) {
            $model = ModelReference::fromString($data['item'])->instance();
            $copiedModel = app(DuplicatePage::class)->handle($model, $resource->getTitleAttributeKey());

            Audit::activity()->performedOn($model)->log('duplicated');
            return redirect()->to($manager->route('edit', $copiedModel))->with('messages.success', $resource->getPageTitle($model) . ' is gekopieerd.');
        })->redirectTo(function ($formData, $data) use ($manager) {
            return $manager->route('edit', ModelReference::fromString($data['item'])->id());
        });
//            ->call('POST', function ($model) use ($manager) {
//                return $manager->route('duplicate', $model);
//            });
    }
}
