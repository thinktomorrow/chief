<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

class ArticlePageWithBaseSegments extends ArticlePage
{
    protected static $baseUrlSegment = [
        'nl' => 'artikels',
        'en' => 'articles',
    ];
}
