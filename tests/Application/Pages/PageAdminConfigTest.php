<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Thinktomorrow\Chief\Admin\AdminConfig;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class PageAdminConfigTest extends ChiefTestCase
{
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
