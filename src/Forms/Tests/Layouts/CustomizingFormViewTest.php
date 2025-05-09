<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\Layouts;

use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class CustomizingFormViewTest extends FormsTestCase
{
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
