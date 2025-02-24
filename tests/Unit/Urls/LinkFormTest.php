<?php

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class LinkFormTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_the_fixed_base_segment_is_prepended_to_the_slug()
    {
        $model = $this->setupAndCreateArticleWithBaseSegments();

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
