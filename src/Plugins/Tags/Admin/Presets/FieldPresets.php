<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Tags\Admin\Presets;

use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\Application\Taggable\TaggableRepository;

class FieldPresets
{
    public static function tags($model): iterable
    {
        yield Form::make('tags')
            ->title('Tags')
            ->position('aside')
            ->items([
                MultiSelect::make('tags')
                    ->multiple()
                    ->options(fn () => app(TagReadRepository::class)->getAllForSelect())
                    ->value($model->getTags()->map(fn (TagRead $tag) => $tag->getTagId())->all())
                    ->save(function ($_model, $field, $input) {
                        app(TaggableRepository::class)->syncTags($_model, (array) $input['tags']);
                    })->tag('not-on-create'),
            ]);
    }
}
