<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\App\Http\Requests\MenuRequest;
use Thinktomorrow\Chief\Site\Menu\Events\MenuItemCreated;
use Thinktomorrow\Chief\Shared\Concerns\Translatable\TranslatableCommand;
use Thinktomorrow\Chief\Shared\Helpers\Form;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReferenceCollection;
use Thinktomorrow\Chief\Site\Menu\MenuItem;

class CreateMenuItem
{
    use TranslatableCommand;

    public function handle(MenuRequest $request): MenuItem
    {
        try {
            DB::beginTransaction();

            $model = MenuItem::create();
            $model->type = $request->input('type', null);
            $model->parent_id = ($request->input('allow_parent') && $request->input('parent_id')) ? $request->input('parent_id') : null;
            $model->menu_type = $request->input('menu_type', 'main');

            if ($request->input('owner_reference')) {
                $owner = ModelReference::fromString($request->input('owner_reference'));
                $model->owner_type = $owner->shortClassName();
                $model->owner_id = $owner->id();
            }

            Form::foreachTrans($request->input('trans', []), function ($locale, $key, $value) use ($model) {
                $model->setDynamic($key, $value, $locale);
            });

            $model->save();

            event(new MenuItemCreated((string) $model->id));

            DB::commit();

            return $model->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    private function getPage($modelReference)
    {
        return ModelReferenceCollection::fromModelReferences([$modelReference])->first();
    }
}
