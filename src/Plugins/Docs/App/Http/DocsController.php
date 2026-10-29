<?php

namespace Thinktomorrow\Chief\Plugins\Docs\App\Http;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Plugins\Docs\GuideTreeBuilder;
use Thinktomorrow\Chief\Plugins\Docs\Markdown;

class DocsController
{
    public function __construct(
        private Markdown $markdown,
        private DocSources $sources,
        private GuideTreeBuilder $treeBuilder,
    ) {}

    public function index(): View
    {
        return view('chief-docs::index', [
            'tree' => $this->treeBuilder->build($this->sources),
        ]);
    }

    public function show(string $guideId): View
    {
        $tree = $this->treeBuilder->build($this->sources);
        $guide = $tree->find($guideId);

        abort_unless($guide && $guide->hasPage(), 404);

        return view('chief-docs::show', [
            'tree' => $tree,
            'guide' => $guide,
            'content' => $this->markdown->convert($guide->markdown),
        ]);
    }
}
