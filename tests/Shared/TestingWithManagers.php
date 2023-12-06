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

//    protected function addFragment($fragment, $owner)
//    {
//        $this->asAdmin()->post($this->manager($fragment)->route('fragment-add', $owner, $fragment));
//    }

    protected function manager($managedModel): Manager
    {
        if (is_object($managedModel)) {
            $managedModel = $managedModel::class;
        }

        return app(Registry::class)->findManagerByModel($managedModel);
    }


}
