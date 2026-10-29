<?php

namespace Thinktomorrow\Chief\Plugins\Docs\App\Http;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;
use Thinktomorrow\Chief\Plugins\Docs\Markdown;
use Thinktomorrow\Chief\Plugins\Docs\Media\AssetType;
use Thinktomorrow\Chief\Plugins\Docs\Media\GuideAsset;

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
            'content' => $this->markdown->convertGuide($guide, $tree),
        ]);
    }

    /**
     * Stream an image or video of a guide source. An SVG is sandboxed so it can't run scripts in the admin.
     */
    public function asset(Request $request, DocSources $sources, GuideTree $tree, string $assetId): BinaryFileResponse
    {
        $asset = GuideAsset::fromId($sources, $assetId);
        $file = $asset?->file();

        abort_unless($asset && $file && $asset->isVisibleIn($tree), 404);

        $response = new BinaryFileResponse($file, 200, [
            'Content-Type' => $asset->type->contentType(),
            'X-Content-Type-Options' => 'nosniff',
        ]);

        if ($asset->type === AssetType::Svg) {
            $response->headers->set('Content-Security-Policy', 'sandbox');
        }

        $response->setPrivate();
        $response->setAutoLastModified();
        $response->setEtag(hash('xxh128', $file.'|'.filemtime($file).'|'.filesize($file)));
        $response->isNotModified($request);

        return $response;
    }
}
