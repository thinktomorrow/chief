<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Urls\App\Listeners\CreatePageFirstUrls;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

class CreatingPageFirstUrlsTest extends ChiefTestCase
{
    private CreatePageFirstUrls $listener;

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

        $this->listener = app(CreatePageFirstUrls::class);
    }

    public function test_it_can_create_url(): void
    {
        config()->set('chief.sites', [
            ['locale' => 'nl'],
        ]);

        $model = ArticlePage::create([
            'title' => ['nl' => 'foo bar'],
        ]);
        $this->listener->onManagedModelCreated(new ManagedModelCreated($model->modelReference()));

        $this->assertCount(1, $model->fresh()->urls);
        $this->assertEquals('nl', $model->urls->first()->site);
        $this->assertEquals(LinkStatus::offline->value, $model->urls->first()->status);
        $this->assertEquals('nl-base/'.Str::slug('foo bar'), $model->urls->first()->slug);
    }

    public function test_it_can_create_url_for_all_allowed_sites(): void
    {
        $model = ArticlePage::create([
            'allowed_sites' => ['nl', 'en', 'fr'],
            'title' => ['nl' => 'foo bar', 'en' => 'baz bak'],
        ]);
        $this->listener->onManagedModelCreated(new ManagedModelCreated($model->modelReference()));

        $this->assertCount(3, $model->fresh()->urls);
        $this->assertEquals('nl-base/'.Str::slug('foo bar'), $model->urls[0]->slug);
        $this->assertEquals('en-base/'.Str::slug('baz bak'), $model->urls[1]->slug);
        $this->assertEquals('fr-base/'.Str::slug('article page'), $model->urls[2]->slug);
    }
}
