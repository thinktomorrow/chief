<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Thinktomorrow\Chief\Plugins\Docs\Callouts\Callout;
use Thinktomorrow\Chief\Plugins\Docs\GuideNode;
use Thinktomorrow\Chief\Plugins\Docs\GuideSurface;
use Thinktomorrow\Chief\Plugins\Docs\Media\GuideAsset;

/**
 * A surface outside the admin, serving guides under `https://example.test/gidsen`.
 */
final class FakeGuideSurface implements GuideSurface
{
    public function __construct(
        private readonly bool $rendersOwnCallouts = false,
    ) {}

    public function guideUrl(GuideNode $guide, ?string $fragment = null): string
    {
        return 'https://example.test/gidsen/'.$guide->id.($fragment !== null ? '#'.$fragment : '');
    }

    public function assetUrl(GuideAsset $asset): string
    {
        return 'https://example.test/gidsen/'.$asset->id();
    }

    public function configure(Environment $environment): void
    {
        if (! $this->rendersOwnCallouts) {
            return;
        }

        $environment->addRenderer(Callout::class, new class implements NodeRendererInterface
        {
            public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
            {
                return '<div class="eigen-callout">'.$childRenderer->renderNodes($node->children()).'</div>';
            }
        }, 10);
    }
}
