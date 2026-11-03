<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Media;

use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Streams an image or video of a guide source. An SVG is sandboxed so it can't
 * run scripts on the surface that serves it. The admin caches an asset privately.
 * A public surface caches a URL versioned with `?v=` for a year and revalidates
 * any other URL on every request.
 *
 * Each surface checks that the asset is visible in its tree before responding.
 */
final class GuideAssetResponse
{
    public static function make(GuideAsset $asset, Request $request, bool $public = false): BinaryFileResponse
    {
        $file = $asset->file() ?? throw new InvalidArgumentException('Guide asset ['.$asset->id().'] does not exist.');

        $response = new BinaryFileResponse($file, 200, [
            'Content-Type' => $asset->type->contentType(),
            'X-Content-Type-Options' => 'nosniff',
        ]);

        if ($asset->type === AssetType::Svg) {
            $response->headers->set('Content-Security-Policy', 'sandbox');
        }

        if (! $public) {
            $response->setPrivate();
        } elseif ($request->query->has('v')) {
            $response->setPublic();
            $response->setMaxAge(31536000);
            $response->setImmutable();
        } else {
            $response->setPublic();
            $response->setMaxAge(0);
            $response->headers->addCacheControlDirective('must-revalidate');
        }

        $response->setAutoLastModified();
        $response->setEtag(hash('xxh128', $file.'|'.filemtime($file).'|'.filesize($file)));
        $response->isNotModified($request);

        return $response;
    }
}
