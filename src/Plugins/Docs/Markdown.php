<?php

namespace Thinktomorrow\Chief\Plugins\Docs;

use League\CommonMark\CommonMarkConverter;

class Markdown
{
    public function convert(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return $converter->convert($value)->getContent();
    }
}
