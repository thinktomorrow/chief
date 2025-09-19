<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\TaggableRepository;
use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagModel;
use Thinktomorrow\Chief\Table\Actions\BulkAction;

class DetachTagAction extends BulkAction
{
    public static function makeDefault(string $resourceKey): static
    {
        return static::make('detach-tag')
            ->label('Tags verwijderen')
            ->dialog(function ($data) use ($resourceKey) {
                $modelIds = $data['items'];

                // Get all tags belonging to the model selection
                $tagsForSelect = TagModel::join('chief_tags_pivot', 'chief_tags.id', '=', 'chief_tags_pivot.tag_id')
                    ->where('chief_tags_pivot.owner_type', $resourceKey)
                    ->whereIn('chief_tags_pivot.owner_id', $modelIds)
                    ->get()
                    ->mapWithKeys(fn (TagModel $model) => [$model->id => $model->label])
                    ->all();

                return Dialog::make('tagModal')
                    ->title('Verwijder tags van aan selectie')
                    ->subTitle(count($modelIds).' items geselecteerd')
                    ->form([
                        MultiSelect::make('tags')
                            ->multiple()
                            ->options($tagsForSelect),
                    ])
                    ->button('Verwijderen')
                    ->buttonVariant('red');

            })
            ->effect(function ($formData, $data) use ($resourceKey) {
                $tagIds = (array) ($formData['tags'] ?? []);
                $modelIds = $data['items'];

                try {
                    app(TaggableRepository::class)->detachTags($resourceKey, $modelIds, $tagIds);

                    return true;
                } catch (\Exception $e) {
                    report($e);

                    return false;
                }
            })
            ->notifyOnSuccess('Tags verwijderd!')
            ->notifyOnFailure('Er is iets misgegaan bij het verwijderen van de tags.');
    }
}
