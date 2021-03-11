<?php


namespace Thinktomorrow\Chief\Tests\Shared;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Fragments\FragmentsRenderer;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Fragments\Actions\CreateFragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageWithFileValidation;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageWithImageValidation;

trait TestingWithManagers
{
    protected function setupAndCreateArticle(array $values = []): ArticlePage
    {
        ArticlePage::migrateUp();

        chiefRegister()->model(ArticlePage::class, PageManager::class);

        return ArticlePage::create($values);
    }

    protected function setupAndCreateArticleWithRequiredFile(array $values = []): ArticlePage
    {
        ArticlePageWithFileValidation::migrateUp();

        chiefRegister()->model(ArticlePageWithFileValidation::class, PageManager::class);

        return ArticlePageWithFileValidation::create($values);
    }

    protected function setupAndCreateArticleWithRequiredImage(array $values = []): ArticlePage
    {
        ArticlePageWithImageValidation::migrateUp();

        chiefRegister()->model(ArticlePageWithImageValidation::class, PageManager::class);

        return ArticlePageWithImageValidation::create($values);
    }

    protected function setupAndCreateQuote(FragmentsOwner $owner, array $values = [], $withSetup = true): Quote
    {
        if ($withSetup) {
            Quote::migrateUp();
            chiefRegister()->model(Quote::class, FragmentManager::class);
        }

        $quote = Quote::create($values);

        return $this->addAsFragment($quote, $owner);
    }

    protected function addAsFragment($model, $owner)
    {
        return $model->setFragmentModel(
            app(CreateFragmentModel::class)->create($owner, $model, 1)
        );
    }

    protected function manager($managedModel): Manager
    {
        if (is_object($managedModel)) {
            $managedModel = $managedModel::managedModelKey();
        }

        return app(Registry::class)->manager($managedModel);
    }

    protected function assertFragmentCount(Model $owner, int $count)
    {
        $this->assertCount($count, app(FragmentRepository::class)->getByOwner($owner));
    }

    protected function assertRenderedFragments(Model $owner, string $expected)
    {
        $this->assertEquals($expected, app(FragmentsRenderer::class)->render($owner, []));
    }

    protected function firstFragment(Model $owner, callable $callback = null)
    {
        $fragments = app(FragmentRepository::class)->getByOwner($owner);

        if(!$fragments->first()) {
            throw new \Exception('Test failed. Owner doesnt have any fragments.');
        }

        if($callback) $callback($fragments->first());

        return $fragments->first();
    }
}
