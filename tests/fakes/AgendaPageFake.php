<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Common\TranslatableFields\HtmlField;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Traits\HasPeriodTrait;

class AgendaPageFake extends Page
{
    use HasPeriodTrait;
    
    public function menuUrl(): string
    {
        return route('articles.show', $this->slug);
    }

    public static function customTranslatableFields(): array
    {
        return [
            'content' => HtmlField::make(),
        ];
    }
}
