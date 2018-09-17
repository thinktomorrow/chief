<?php

namespace Thinktomorrow\Chief\Snippets;

trait WithSnippets
{
    public $withSnippets = false;

    public function constructWithSnippets()
    {
        $this->withSnippets = config('thinktomorrow.chief.withSnippets',false);
    }

    public function withSnippets()
    {
        $this->withSnippets = true;

        return $this;
    }

    public function shouldParseWithSnippets($value): bool
    {
        return ($this->withSnippets && is_string($value) && false !== strpos($value, '[['));
    }

    public function parseWithSnippets($value)
    {
        return SnippetParser::parse($value);
    }
}