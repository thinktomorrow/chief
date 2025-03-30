<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Tags\App\Presets;

use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\TaggableRepository;

class FieldPresets
{
    public static function tags($model, ?Field $field = null): iterable
    {
        yield Form::make('tags')
            ->title('Tags')
            ->position('aside')
            ->items([
                $field ?: static::tagSelect($model),
            ]);
    }

    public static function tagSelect($model): MultiSelect
    {
        return MultiSelect::make('tags')
            ->multiple()
            ->options(fn () => app(TagReadRepository::class)->getAllForSelect())
            ->value($model->getTags()->map(fn (TagRead $tag) => $tag->getTagId())->all())
            ->save(function ($_model, $field, $input) {
                app(TaggableRepository::class)->syncTags($_model->getMorphClass(), [$_model->getKey()], (array) ($input['tags'] ?? []));
            })->tag('not-on-create');
    }
}
