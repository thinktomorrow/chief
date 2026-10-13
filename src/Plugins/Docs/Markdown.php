<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

use League\CommonMark\GithubFlavoredMarkdownConverter;

class Markdown
{
    public function convert(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return $converter->convert($value)->getContent();
    }
}
