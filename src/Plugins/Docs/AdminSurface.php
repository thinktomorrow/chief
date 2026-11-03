<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

use League\CommonMark\Environment\Environment;
use Thinktomorrow\Chief\Plugins\Docs\Callouts\AdminCalloutRenderer;
use Thinktomorrow\Chief\Plugins\Docs\Callouts\Callout;
use Thinktomorrow\Chief\Plugins\Docs\Media\GuideAsset;

/**
 * Shows guides in the admin, on the `chief.docs.show` and `chief.docs.asset` routes,
 * with callouts as Chief's callout component.
 */
final class AdminSurface implements GuideSurface
{
    public function guideUrl(GuideNode $guide, ?string $fragment = null): string
    {
        return route('chief.docs.show', $guide->id).($fragment !== null ? '#'.$fragment : '');
    }

    public function assetUrl(GuideAsset $asset): string
    {
        return route('chief.docs.asset', $asset->id());
    }

    public function configure(Environment $environment): void
    {
        $environment->addRenderer(Callout::class, new AdminCalloutRenderer, 10);
    }
}
