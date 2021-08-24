<?php

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageWithBaseSegments;

class LinkFormTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function the_fixed_base_segment_is_prepended_to_the_slug()
    {
        ArticlePageWithBaseSegments::migrateUp();
        app(Register::class)->model(ArticlePageWithBaseSegments::class, PageManager::class);
        $model = ArticlePageWithBaseSegments::create();

        $this->updateLinks($model, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        // Assert base segments are present in urls
        $this->assertStringEndsWith('articles/foobar-en', $model->fresh()->url('en'));
        $this->assertStringEndsWith('artikels/foobar-nl', $model->fresh()->url('nl'));

        $linkForm = LinkForm::fromModel($model);

        $this->assertEquals('http://localhost/articles/', $linkForm->formValues()['en']->host);
        $this->assertEquals('articles', $linkForm->formValues()['en']->fixedSegment);
        $this->assertEquals('foobar-en', $linkForm->formValues()['en']->value);

        $this->assertEquals('http://localhost/artikels/', $linkForm->formValues()['nl']->host);
        $this->assertEquals('artikels', $linkForm->formValues()['nl']->fixedSegment);
        $this->assertEquals('foobar-nl', $linkForm->formValues()['nl']->value);
    }
}
