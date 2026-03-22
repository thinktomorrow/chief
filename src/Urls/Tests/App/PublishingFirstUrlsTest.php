<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPublished;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Urls\App\Listeners\CreatePageFirstUrls;
use Thinktomorrow\Chief\Urls\App\Listeners\PublishFirstUrls;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

class PublishingFirstUrlsTest extends ChiefTestCase
{
    private CreatePageFirstUrls $createPageFirstUrls;

    private PublishFirstUrls $publishFirstUrls;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);

        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Text::make('title')->locales()->required(),
                ]),
            ];
        });

        config()->set('chief.sites', [
            ['locale' => 'nl'],
            ['locale' => 'en'],
        ]);

        $this->createPageFirstUrls = app(CreatePageFirstUrls::class);
        $this->publishFirstUrls = app(PublishFirstUrls::class);
    }

    public function test_it_puts_links_online_on_first_publish(): void
    {
        $model = ArticlePage::create([
            'title' => ['nl' => 'foo bar', 'en' => 'foo bar en'],
        ]);

        $this->createPageFirstUrls->onManagedModelCreated(new ManagedModelCreated($model->modelReference()));

        $this->assertEqualsCanonicalizing(
            [LinkStatus::offline->value, LinkStatus::offline->value],
            $model->fresh()->urls->pluck('status')->all()
        );

        $this->publishFirstUrls->onManagedModelPublished(new ManagedModelPublished($model->modelReference()));

        $this->assertEqualsCanonicalizing(
            [LinkStatus::online->value, LinkStatus::online->value],
            $model->fresh()->urls->pluck('status')->all()
        );
    }

    public function test_it_does_not_change_other_links_when_already_online_before_publish(): void
    {
        $model = ArticlePage::create([
            'title' => ['nl' => 'foo bar', 'en' => 'foo bar en'],
        ]);

        $this->createPageFirstUrls->onManagedModelCreated(new ManagedModelCreated($model->modelReference()));

        $nlLink = $model->fresh()->urls->firstWhere('site', 'nl');
        $nlLink->changeStatus(LinkStatus::online);
        $nlLink->save();

        $this->publishFirstUrls->onManagedModelPublished(new ManagedModelPublished($model->modelReference()));

        $this->assertEquals(LinkStatus::online->value, $model->fresh()->urls->firstWhere('site', 'nl')->status);
        $this->assertEquals(LinkStatus::offline->value, $model->fresh()->urls->firstWhere('site', 'en')->status);
    }
}
