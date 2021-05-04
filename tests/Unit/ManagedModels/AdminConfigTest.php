<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\ManagedModels;

use Thinktomorrow\Chief\Admin\AdminConfig;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\TestCase;

class AdminConfigTest extends TestCase
{
    /** @test */
    public function it_contains_values_for_admin_views()
    {
        $config = AdminConfig::make();

        $config->paginate(2);
        $config->indexTitle('index_title');
        $config->pageTitle('page_title');
        $config->navTitle('nav_title');
        $config->modelName('model_name');
        $config->rowContent('row_content');
        $config->rowTitle('row_title');

        $this->assertEquals(2, $config->getPagination());
        $this->assertEquals('index_title', $config->getIndexTitle());
        $this->assertEquals('page_title', $config->getPageTitle());
        $this->assertEquals('nav_title', $config->getNavTitle());
        $this->assertEquals('model_name', $config->getModelName());
        $this->assertEquals('row_content', $config->getRowContent());
        $this->assertEquals('row_title', $config->getRowTitle());
    }

    /** @test */
    public function it_returns_defaults_for_model()
    {
        $model = ArticlePage::make([
            'title' => 'first article',
        ]);

        /** @var AdminConfig $config */
        $config = $model->adminConfig();

        $this->assertEquals('article pages', $config->getIndexTitle());
        $this->assertEquals('first article', $config->getPageTitle());
        $this->assertEquals('article page', $config->getModelName());
    }
}
