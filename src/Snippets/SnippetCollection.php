<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Snippets;

use Illuminate\Support\Collection;

class SnippetCollection
{
    /** @var Collection */
    private $snippets;

    public function __construct(Collection $snippets)
    {
        $this->snippets = $snippets;
    }

    public static function all()
    {
        // Glob viewfolder

        // Parse each to snippet class
    }


}