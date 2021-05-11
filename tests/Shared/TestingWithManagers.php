<?php


namespace Thinktomorrow\Chief\Tests\Shared;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Actions\CreateFragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\FragmentsRenderer;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageWithFileValidation;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageWithImageValidation;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

trait TestingWithManagers
{
    protected function setupAndCreateArticle(array $values = [], bool $withSetup = true): ArticlePage
    {
        if ($withSetup) {
            ArticlePage::migrateUp();
            chiefRegister()->model(ArticlePage::class, PageManager::class);
        }

        return ArticlePage::create($values);
    }

    protected function setupAndCreateQuote(FragmentsOwner $owner, array $values = [], $order = 0, $withSetup = true): Quote
    {
        if ($withSetup) {
            Quote::migrateUp();
            chiefRegister()->model(Quote::class, FragmentManager::class);
        }

        $quote = Quote::create($values);

        return $this->createAsFragment($quote, $owner, $order);
    }

    protected function setupAndCreateSnippet(FragmentsOwner $owner, $order = 0, $withSetup = true): SnippetStub
    {
        if ($withSetup) {
            chiefRegister()->staticFragment(SnippetStub::class);
        }

        $snippet = new SnippetStub();

        return $this->createAsFragment($snippet, $owner, $order);
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

    protected function createAsFragment($model, $owner, $order = 0)
    {
        return $model->setFragmentModel(app(CreateFragmentModel::class)->create($owner, $model, $order));
    }

    protected function addFragment($fragment, $owner)
    {
        $this->asAdmin()->post($this->manager($fragment)->route('fragment-add', $owner, $fragment));
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

        if (! $fragments->first()) {
            throw new \Exception('Test failed. Owner doesn\'t own any fragments.');
        }

        if ($callback) {
            $callback($fragments->first());
        }

        return $fragments->first();
    }
}
