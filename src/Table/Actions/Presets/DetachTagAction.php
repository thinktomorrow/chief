<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\TaggableRepository;
use Thinktomorrow\Chief\Table\Actions\Action;

class DetachTagAction extends Action
{

    public static function makeDefault(string $resourceKey): static
    {
        return static::make('detach-tag')
                ->label('Verwijder tags')
                ->dialog(
                    Dialog::make('tagModal')
                        ->title('Verwijder tags van aan selectie')
                        // TODO(ben): make it so that the subtitle of a bulk action modal displays the amount of selected items
                        ->subTitle(':count items geselecteerd')
                        ->form([
                            MultiSelect::make('tags')
                                ->multiple()
                                ->options(fn () => app(TagReadRepository::class)->getAllForSelect()),
                        ])
                        ->button('Verwijderen')
                )->effect(function ($formData, $data) use ($resourceKey) {

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
