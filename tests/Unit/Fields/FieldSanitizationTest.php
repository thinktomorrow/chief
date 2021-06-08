<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;

class FieldSanitizationTest extends ChiefTestCase
{
    /** @test */
    public function a_field_can_sanitize_its_value()
    {
        $field = InputField::make('title')->sanitize(function(){
            return 'foobar';
        });

        $this->assertEquals('foobar', $field->getSanitizedValue('input-value'));
    }

    /** @test */
    public function it_can_sanitize_a_value()
    {
        $article = $this->setupAndCreateArticle();

        $this->asAdmin()->put($this->manager($article)->route('update', $article), [
            'title' => 'new title',
            'custom' => 'custom value',
            'trans' => [
                'nl' => [
                    'content_trans' => 'nl content',
                ],
            ],
            'title_sanitized' => '',
        ]);

        $this->assertEquals('new-title', $article->fresh()->title_sanitized);
    }

    /** @test */
    public function it_can_sanitize_localized_values()
    {
        $article = $this->setupAndCreateArticle();

        $this->asAdmin()->put($this->manager($article)->route('update', $article), [
            'title' => 'new title',
            'custom' => 'custom value',
            'trans' => [
                'nl' => [
                    'content_trans' => 'nl content',
                    'title_sanitized_trans' => '',
                ],
                'en' => [
                    'title_sanitized_trans' => 'title-sanitized-en',
                ],
            ],

        ]);

        $this->assertEquals('new-title-nl', $article->fresh()->title_sanitized_trans);
        $this->assertEquals('title-sanitized-en', $article->fresh()->dynamic('title_sanitized_trans','en'));
    }
}
