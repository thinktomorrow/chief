<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\Layouts;

use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class FormTest extends FormsTestCase
{
    public function test_it_can_set_form_component()
    {
        $component = Form::make('general')->components([
            new Textarea('intro'),
        ]);

        $this->assertCount(1, $component->getComponents());
        $this->assertIsString($component->toHtml());
    }

    public function test_it_can_have_a_custom_view()
    {
        $this->app['view']->addNamespace('test-views', __DIR__.'/../TestSupport/stubs/views');

        $this->assertStringContainsString(
            'general form view',
            Form::make('general')
                ->setView('test-views::custom-form')
                ->toHtml()
        );
    }
}
