<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Pages\Page;

class ArticlePageFake extends Page
{
    public function menuUrl(): string
    {
        return route('articles.show', $this->slug);
    }
}
