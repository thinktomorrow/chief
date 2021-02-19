<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Snippets;

class SnippetParser
{
    private static $pattern = '#\[\[(.+?)\]\]#';

    public static function parse($value)
    {
        if (! is_string($value)) {
            return $value;
        }

        $value = preg_replace_callback(static::$pattern, function ($matches) {

            // First entry of matches contains our full captured group, which we want to replace.
            // Second entry is the text itself, without the brackets
            return static::replaceWithSnippet($matches[0], $matches[1]);
        }, $value);

        return $value;
    }

    private static function replaceWithSnippet($placeholder, $snippetKey)
    {
        if (! $snippet = SnippetCollection::find($snippetKey)) {
            return $placeholder;
        }

        return $snippet->render();
    }
}
