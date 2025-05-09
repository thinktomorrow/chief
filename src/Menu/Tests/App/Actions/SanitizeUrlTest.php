<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Actions;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\Menu\App\Actions\SanitizeUrl;
use Thinktomorrow\Url\Url;

class SanitizeUrlTest extends TestCase
{
    private SanitizeUrl $sanitizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sanitizer = new SanitizeUrl;
    }

    public function test_it_sanitizes_relative_urls(): void
    {
        $this->assertEquals('/about', $this->sanitizer->sanitize('about'));
        $this->assertEquals('/about', $this->sanitizer->sanitize('/about'));
        $this->assertEquals('/about/us', $this->sanitizer->sanitize('about/us'));
        $this->assertEquals('/about/us', $this->sanitizer->sanitize('/about/us/'));
    }

    public function test_it_returns_absolute_urls_unchanged(): void
    {
        $this->assertEquals('https://example.com', $this->sanitizer->sanitize('https://example.com'));
        $this->assertEquals('http://example.com', $this->sanitizer->sanitize('http://example.com'));
    }

    public function test_it_returns_mailto_and_tel_urls_unchanged(): void
    {
        $this->assertEquals('mailto:test@example.com', $this->sanitizer->sanitize('mailto:test@example.com'));
        $this->assertEquals('tel:+1234567890', $this->sanitizer->sanitize('tel:+1234567890'));
    }

    public function test_it_respects_non_secure_urls(): void
    {
        $mockUrl = $this->createMock(Url::class);
        $mockUrl->method('secure')->willReturnSelf();
        $mockUrl->method('get')->willReturn('https://example.com');

        $this->assertEquals('http://example.com', $this->sanitizer->sanitize('http://example.com'));
    }

    public function test_it_handles_edge_cases(): void
    {
        $this->assertEquals('/', $this->sanitizer->sanitize('/'));
        $this->assertEquals('/test', $this->sanitizer->sanitize('//test')); // Double slashes should be cleaned up
        $this->assertEquals('/test/path', $this->sanitizer->sanitize('test/path/'));
        $this->assertEquals('/test/path', $this->sanitizer->sanitize('/test/path'));
    }
}
