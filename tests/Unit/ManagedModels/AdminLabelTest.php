<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\ManagedModels;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class AdminLabelTest extends TestCase
{
    /** @test */
    public function it_returns_a_generic_label()
    {
        $model = new ArticlePage();

        $this->assertEquals('article page', $model->adminLabel('label'));
        $this->assertEquals('article page', $model->adminLabel('nav_label'));
        $this->assertEquals('article pages', $model->adminLabel('page_title'));
    }

    /** @test */
    public function it_returns_a_instance_label()
    {
        $model = ArticlePage::make([
            'title' => 'first article',
        ]);

        $this->assertEquals('first article', $model->adminLabel('title'));
    }
}
