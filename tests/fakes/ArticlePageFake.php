<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Common\Fields\HtmlField;
use Thinktomorrow\Chief\Common\Relations\ActsAsParent;
use Thinktomorrow\Chief\Pages\Page;

class ArticlePageFake extends Page
{
    public function menuUrl(): string
    {
        return route('articles.show', $this->slug);
    }

    public static function customTranslatableFields(): array
    {
        return [
            HtmlField::make('content'),
        ];
    }

    public function presentForParent(ActsAsParent $parent): string
    {
        return $this->content;
    }
}
