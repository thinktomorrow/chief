<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Plugins\Docs\Media\GuideAsset;
use Thinktomorrow\Chief\Plugins\Docs\Media\GuideAssetResponse;

class GuideAssetResponseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useOnlyProjectGuides($this->fixture('media'));
    }

    public function test_the_admin_caches_an_asset_privately(): void
    {
        $response = $this->respond('opleidingen/schermen/aanmaken.png', '/beeld.png?v=1');

        $this->assertTrue($response->headers->hasCacheControlDirective('private'));
        $this->assertFalse($response->headers->hasCacheControlDirective('public'));
        $this->assertFalse($response->headers->hasCacheControlDirective('immutable'));
    }

    public function test_a_public_versioned_asset_is_cached_for_a_year(): void
    {
        $response = $this->respond('opleidingen/schermen/aanmaken.png', '/beeld.png?v=1', public: true);

        $this->assertTrue($response->headers->hasCacheControlDirective('public'));
        $this->assertSame('31536000', $response->headers->getCacheControlDirective('max-age'));
        $this->assertTrue($response->headers->hasCacheControlDirective('immutable'));
        $this->assertFalse($response->headers->hasCacheControlDirective('private'));
    }

    public function test_a_public_unversioned_asset_is_revalidated_on_every_request(): void
    {
        $response = $this->respond('opleidingen/schermen/aanmaken.png', '/beeld.png', public: true);

        $this->assertTrue($response->headers->hasCacheControlDirective('public'));
        $this->assertSame('0', $response->headers->getCacheControlDirective('max-age'));
        $this->assertTrue($response->headers->hasCacheControlDirective('must-revalidate'));
        $this->assertFalse($response->headers->hasCacheControlDirective('immutable'));
    }

    public function test_every_asset_is_served_as_its_type_with_validators(): void
    {
        foreach ([false, true] as $public) {
            $response = $this->respond('opleidingen/schermen/aanmaken.png', '/beeld.png', $public);

            $this->assertSame('image/png', $response->headers->get('Content-Type'));
            $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
            $this->assertNotEmpty($response->headers->get('ETag'));
            $this->assertNotEmpty($response->headers->get('Last-Modified'));
            $this->assertFalse($response->headers->has('Content-Security-Policy'));
        }
    }

    public function test_an_svg_is_sandboxed_on_every_surface(): void
    {
        foreach ([false, true] as $public) {
            $response = $this->respond('opleidingen/bestanden/voorbeeld.svg', '/beeld.svg', $public);

            $this->assertSame('sandbox', $response->headers->get('Content-Security-Policy'));
        }
    }

    private function respond(string $assetId, string $uri, bool $public = false): BinaryFileResponse
    {
        $asset = GuideAsset::fromId($this->app->make(DocSources::class), $assetId);

        return GuideAssetResponse::make($asset, Request::create($uri), $public);
    }
}
