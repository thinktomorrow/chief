<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Application\Pages\Astrotomic\QuoteWithAstrotomicTranslations;
use function route;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UpdatePageTest extends ChiefTestCase
{
    use PageFormParams;

    private ArticlePage $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setupAndCreateArticle([
            'title' => 'Originele titel',
        ]);
    }

    /** @test */
    public function it_can_update_a_model()
    {
        $this->asAdmin()->put($this->manager($this->model)->route('update', $this->model), $this->validUpdatePageParams([
            'title' => 'nieuwe titel',
        ]));

        $this->assertEquals('nieuwe titel', $this->model->fresh()->title);
    }

    /** @test */
    public function a_non_admin_cannot_update_a_model()
    {
        $this->put($this->manager($this->model)->route('update', $this->model), $this->validUpdatePageParams([
            'title' => 'nieuwe titel',
        ]))->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));

        $this->assertEquals('Originele titel', $this->model->fresh()->title);
    }

    /** @test */
    public function it_can_update_a_model_with_dynamic_field()
    {
        $this->asAdmin()->put($this->manager($this->model)->route('update', $this->model), $this->validUpdatePageParams([
            'custom' => 'nieuwe titel',
        ]));

        $this->assertEquals('nieuwe titel', $this->model->fresh()->custom);
    }

    /** @test */
    public function it_can_update_a_model_with_dynamic_localized_field()
    {
        $this->asAdmin()->put($this->manager($this->model)->route('update', $this->model), $this->validUpdatePageParams([
            'trans' => [
                'nl' => [
                    'title_trans' => 'nieuwe titel',
                    'content_trans' => 'required content',
                ],
                'en' => ['title_trans' => 'new title'],
            ],
        ]));

        $this->assertEquals(1, $this->model::count());
        $this->assertEquals('nieuwe titel', $this->model->fresh()->title_trans);
        $this->assertEquals('new title', $this->model->fresh()->dynamic('title_trans', 'en'));
    }

    /** @test */
    public function it_can_update_a_model_with_localized_field()
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

    /** @test */
    public function it_can_upload_a_file_field()
    {
        $this->asAdmin()->put($this->manager($this->model)->route('update', $this->model), $this->validUpdatePageParams([
            'files' => [
                'thumb' => [
                    'nl' => [
                        UploadedFile::fake()->image('tt-favicon.png'),
                    ],
                ],
            ],
        ]));

        $this->assertEquals('tt-favicon.png', ArticlePage::first()->asset('thumb')->filename());
    }
}
