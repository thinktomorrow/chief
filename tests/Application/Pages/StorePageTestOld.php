<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\Application\Pages\Astrotomic\QuoteWithAstrotomicTranslations;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;

class StorePageTestOld extends ChiefTestCase
{
    use PageFormParams;

    public function test_it_can_create_a_model()
    {
        $this->asAdmin()->post($this->manager(ArticlePage::class)->route('store'), $this->validPageParams(['title' => 'new-title']));

        $this->assertEquals(1, ArticlePage::count());
        $this->assertEquals('new-title', ArticlePage::first()->title);
    }

    public function test_it_can_create_a_model_with_dynamic_field()
    {
        $model = ArticlePage::class;

        $this->asAdmin()->post($this->manager($model)->route('store'), [
            'title' => 'required titel',
            'custom' => 'new-title',
            'trans' => [
                'nl' => [
                    'content_trans' => 'nl content',
                ],
            ],
        ]);

        $this->assertEquals(1, $model::count());
        $this->assertEquals('new-title', $model::first()->custom);
    }

    public function test_it_can_create_a_model_with_dynamic_localized_field()
    {
        $model = ArticlePage::class;

        $this->asAdmin()->post($this->manager($model)->route('store'), [
            'title' => 'required titel',
            'custom' => 'new-title',
            'trans' => [
                'nl' => [
                    'title_trans' => 'dynamic title nl',
                    'content_trans' => 'nl content',
                ],
                'en' => [
                    'title_trans' => 'dynamic title en',
                ],
            ],
        ]);

        $this->assertEquals(1, $model::count());
        $this->assertEquals('dynamic title nl', $model::first()->title_trans);
        $this->assertEquals('dynamic title en', $model::first()->dynamic('title_trans', 'en'));
    }

    public function test_it_can_create_a_model_with_localized_field()
    {
        QuoteWithAstrotomicTranslations::migrateUp();
        chiefRegister()->resource(QuoteWithAstrotomicTranslations::class, PageManager::class);

        $model = QuoteWithAstrotomicTranslations::class;

        $this->asAdmin()->post($this->manager($model)->route('store'), [
            'trans' => [
                'nl' => [
                    'title_trans' => 'title nl',
                ],
                'en' => [
                    'title_trans' => 'title en',
                ],
            ],
        ]);

        $this->assertEquals(1, $model::count());
        $this->assertEquals('title nl', $model::first()->{'title_trans:nl'});
        $this->assertEquals('title en', $model::first()->{'title_trans:en'});
    }

    public function test_it_can_upload_a_file_field()
    {
        UploadedFile::fake()->image('tt-favicon.png')->storeAs('test', 'image-temp-name.png');

        $this->asAdmin()->post($this->manager(ArticlePage::class)->route('store'), [
            'title' => 'required titel',
            'custom' => 'new-title',
            'trans' => [
                'nl' => [
                    'content_trans' => 'nl content',
                ],
            ],
            'files' => [
                'thumb' => [
                    'nl' => [
                        'uploads' => [
                            [
                                'id' => 'xxx',
                                'path' => Storage::path('test/image-temp-name.png'),
                                'originalName' => 'tt-favicon.png',
                                'mimeType' => 'image/png',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertEquals('tt-favicon.png', ArticlePage::first()->asset('thumb')->filename());
    }

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class, PageManager::class);
    }
}
