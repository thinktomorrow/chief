<?php


namespace Thinktomorrow\Chief\Tests\Shared;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\App\Queries\FragmentsRenderer;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResourceWithBaseSegments;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResourceWithFileValidation;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResourceWithImageValidation;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageWithBaseSegments;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

trait TestingWithManagers
{
    public function setUpAndCreateArticle(array $values = [], bool $withSetup = true): ArticlePage
    {
        if ($withSetup) {
            ArticlePage::migrateUp();
            chiefRegister()->resource(ArticlePageResource::class, PageManager::class);
        }

        return ArticlePage::create($values);
    }

    public function setUpAndCreateArticleWithBaseSegments(array $values = [], bool $withSetup = true): ArticlePageWithBaseSegments
    {
        if ($withSetup) {
            ArticlePageWithBaseSegments::migrateUp();
            chiefRegister()->resource(ArticlePageResourceWithBaseSegments::class, PageManager::class);
        }

        return ArticlePageWithBaseSegments::create($values);
    }

    public function setUpAndCreateQuote(FragmentsOwner $owner, array $values = [], $order = 0, $withSetup = true, string $locale = 'nl'): Quote
    {
        if ($withSetup) {
            chiefRegister()->resource(Quote::class, FragmentManager::class);
        }

        $context = app(ContextRepository::class)->findByOwner($owner, $locale) ?: app(ContextRepository::class)->createForOwner($owner, $locale);

        return $this->createAndAttachFragment(Quote::resourceKey(), $context->id, $order, $values);
    }

    protected function findOrCreateContext($owner, string $locale = 'nl'): ContextModel
    {
        return app(ContextRepository::class)->findByOwner($owner, $locale) ?: app(ContextRepository::class)->createForOwner($owner, $locale);
    }

    protected function createAndAttachFragment(string $fragmentKey, $contextId, $order = 0, array $data = []): Fragmentable
    {
        $model = (new (Relation::getMorphedModel($fragmentKey)))->setFragmentModel(FragmentModel::find(app(CreateFragment::class)->handle($fragmentKey, $data)));

        app(AttachFragment::class)->handle($contextId, $model->fragmentModel()->id, $order, []);

        return $model;
    }

    public function setUpAndCreateSnippet(FragmentsOwner $owner, $order = 0, $withSetup = true, array $values = [], string $locale = 'nl'): SnippetStub
    {
        if ($withSetup) {
            chiefRegister()->fragment(SnippetStub::class);
        }

        $context = $this->findOrCreateContext($owner, $locale);

        return $this->createAndAttachFragment(SnippetStub::resourceKey(), $context->id, $order, $values);
    }

    public function setUpAndCreateHero(FragmentsOwner $owner, $order = 0, $withSetup = true, string $locale = 'nl'): Hero
    {
        if ($withSetup) {
            chiefRegister()->fragment(Hero::class);
        }

        $context = $this->findOrCreateContext($owner, $locale);

        return $this->createAndAttachFragment(Hero::resourceKey(), $context->id, $order);
    }

    public function setUpAndCreateArticleWithRequiredFile(array $values = []): ArticlePage
    {
        ArticlePage::migrateUp();

        chiefRegister()->resource(ArticlePageResourceWithFileValidation::class, PageManager::class);

        return ArticlePage::create($values);
    }

    public function setUpAndCreateArticleWithRequiredImage(array $values = []): ArticlePage
    {
        ArticlePage::migrateUp();

        chiefRegister()->resource(ArticlePageResourceWithImageValidation::class, PageManager::class);

        return ArticlePage::create($values);
    }

    protected function addFragment($fragment, $owner)
    {
        $this->asAdmin()->post($this->manager($fragment)->route('fragment-add', $owner, $fragment));
    }

    protected function manager($managedModel): Manager
    {
        if (is_object($managedModel)) {
            $managedModel = $managedModel::class;
        }

        return app(Registry::class)->findManagerByModel($managedModel);
    }

    protected function assertFragmentCount(Model $owner, string $locale, int $count)
    {
        $this->assertCount($count, app(FragmentRepository::class)->getByOwner($owner, $locale));
    }

    protected function assertRenderedFragments(Model $owner, string $expected)
    {
        $this->assertEquals($expected, app(FragmentsRenderer::class)->render($owner, []));
    }

    protected function firstFragment(Model $owner, string $locale, callable $callback = null)
    {
        $fragments = app(FragmentRepository::class)->getByOwner($owner, $locale);

        if (! $fragments->first()) {
            throw new Exception('Test failed. Owner doesn\'t own any fragments.');
        }

        if ($callback) {
            $callback($fragments->first());
        }

        return $fragments->first();
    }
}
