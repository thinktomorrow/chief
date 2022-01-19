<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Forms;

use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\TestCase;

class FormTest extends TestCase
{
    /** @test */
    public function it_can_set_form_component()
    {
        $component = Form::make('general')->action('update-endpoint')->components([
            new Textarea('intro'),
        ]);

        $this->assertCount(1, $component->getComponents());
        $this->assertIsString($component->toHtml());
    }

    /** @test */
    public function it_can_have_a_custom_view()
    {
        $this->app['view']->addNamespace('test-views', __DIR__.'/stubs/views');

        $this->assertStringContainsString(
            'this is a custom field view',
            Form::make('general')
                ->model(new ArticlePage())
                ->view('test-views::custom-form')
                ->toHtml()
        );
    }
}
