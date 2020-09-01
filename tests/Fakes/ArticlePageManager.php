<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Management\Assistants\ArchiveAssistant;
use Thinktomorrow\Chief\Management\Assistants\PublishAssistant;
use Thinktomorrow\Chief\Tests\Feature\Assistants\Stubs\FavoriteAssistant;

class ArticlePageManager extends PageManager
{
    protected $assistants = [
        'url'     => UrlAssistant::class,
        'archive' => ArchiveAssistant::class,
        'publish' => PublishAssistant::class,
        'favorite' => FavoriteAssistant::class,
    ];

    public function fields(): Fields
    {
        return parent::fields()->add(
            InputField::make('content')
                ->translatable(['nl', 'en'])
        );
    }
}
