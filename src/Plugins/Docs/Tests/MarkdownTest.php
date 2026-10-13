<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\Plugins\Docs\Markdown;

class MarkdownTest extends TestCase
{
    public function test_it_renders_tables_and_keeps_unsafe_html_and_links_out(): void
    {
        $html = (new Markdown)->convert("| Onderdeel | Status |\n| --- | --- |\n| Inschrijving | **Open** |\n\n<script>alert('x')</script>\n\n[Onveilig](javascript:alert(1))");

        $this->assertStringContainsString('<table>', $html);
        $this->assertStringContainsString('<strong>Open</strong>', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('href="javascript:', $html);
    }
}
