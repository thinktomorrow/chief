<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use InvalidArgumentException;
use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class DocSourcesTest extends ChiefTestCase
{
    public function test_sources_can_be_added_without_the_docs_plugin(): void
    {
        app(DocSources::class)->add('acme', __DIR__.'/fixtures/acme');

        $namespaces = array_map(fn ($source) => $source->namespace, app(DocSources::class)->all());

        $this->assertContains('acme', $namespaces);
    }

    public function test_adding_the_same_namespace_twice_throws(): void
    {
        app(DocSources::class)->add('acme', __DIR__.'/fixtures/acme');

        $this->expectException(InvalidArgumentException::class);

        app(DocSources::class)->add('acme', __DIR__.'/fixtures/other');
    }
}
