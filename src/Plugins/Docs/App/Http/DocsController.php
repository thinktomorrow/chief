<?php

namespace Thinktomorrow\Chief\Plugins\Docs\App\Http;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;
use Thinktomorrow\Chief\Plugins\Docs\Markdown;

class DocsController
{
    public function __construct(
        private Markdown $markdown,
    ) {}

    public function index(GuideTree $tree): View
    {
        return view('chief-docs::index', [
            'tree' => $tree,
        ]);
    }

    public function show(GuideTree $tree, string $guideId): View
    {
        $guide = $tree->find($guideId);

        abort_unless($guide && $guide->hasPage(), 404);

        return view('chief-docs::show', [
            'tree' => $tree,
            'guide' => $guide,
            'content' => $this->markdown->convert($guide->markdown),
        ]);
    }
}
