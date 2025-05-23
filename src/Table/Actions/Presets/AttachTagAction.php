<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\TaggableRepository;
use Thinktomorrow\Chief\Table\Actions\BulkAction;

class AttachTagAction extends BulkAction
{
    public static function makeDefault(string $resourceKey): static
    {
        return static::make('attach-tag')
            ->label('Tags toevoegen')
            ->dialog(
                Dialog::make('tagModal')
                    ->title('Voeg tags toe aan selectie')
                    ->subTitle(':count items geselecteerd')
                    ->content('
                                <p>
                                    Tags helpen je om pagina\'s te groeperen en te filteren.
                                    Kies alle tags die je wil toevoegen aan deze pagina\'s.
                                </p>
                            ')
                    ->form([
                        MultiSelect::make('tags')
                            ->required()
                            ->multiple()
                            ->options(fn () => app(TagReadRepository::class)->getAllForSelect()),
                    ])
                    ->button('Toevoegen')
            )->effect(function ($formData, $data) use ($resourceKey) {

                $tagIds = (array) ($formData['tags'] ?? []);
                $modelIds = $data['items'];

                try {
                    app(TaggableRepository::class)->attachTags($resourceKey, $modelIds, $tagIds);

                    return true;
                } catch (\Exception $e) {
                    report($e);

                    return false;
                }
            })
            ->notifyOnSuccess('Tags toegevoegd!')
            ->notifyOnFailure('Er is iets misgegaan bij het toevoegen van de tags.');
    }
}
