<?php

namespace Thinktomorrow\Chief\Forms\Tests\Livewire;

use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Livewire\Form;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class FormTest extends ChiefTestCase
{
    public function test_it_can_show_form()
    {
        $model = $this->setupAndCreateArticle();

        $render = Livewire::test(Form::class, [$model, 'seo'])
            ->call('render');

        dd($render);
    }

    public function test_it_can_validate_form()
    {

    }

    public function test_it_can_store_form()
    {

    }

    // it must be valid formId
}
