<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

use League\CommonMark\Environment\Environment;
use Thinktomorrow\Chief\Plugins\Docs\Media\GuideAsset;

/**
 * Where guides are shown, such as the admin for editors or a public site for
 * visitors. A surface decides the URLs of guides and assets, and may change how
 * a guide renders there.
 */
interface GuideSurface
{
    /**
     * @param  null|string  $fragment  A heading anchor without the `#`.
     */
    public function guideUrl(GuideNode $guide, ?string $fragment = null): string;

    /** A bare URL; Chief appends `?v=<mtime>`. */
    public function assetUrl(GuideAsset $asset): string;

    /** Registers surface-specific renderers on the CommonMark environment. */
    public function configure(Environment $environment): void;
}
