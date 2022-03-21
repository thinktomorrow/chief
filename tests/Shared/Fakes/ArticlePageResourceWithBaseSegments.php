<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

class ArticlePageResourceWithBaseSegments extends ArticlePageResource
{
    public static function modelClassName(): string
    {
        return ArticlePageWithBaseSegments::class;
    }
}
