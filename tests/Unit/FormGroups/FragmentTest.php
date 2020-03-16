<?php

namespace Thinktomorrow\Chief\Tests\Unit\FormGroups;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\FormGroups\FormGroup;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\TestCase;


// $page->title
// $page->cards // Collection
// $page->cards->first()->title

// Fragments...
// id key owner_type owner_id order
// 1 - cards - page - 223 - 1
// 2 - cards - page - 223 - 2

// chief_values
// id locale key value (json)
// 1 - nl - 1 - title - "card title 1" - fragment@1
// 2 - nl - 1 - content - "card content 1" - fragment@1

// 3 - nl - 2 - title - "card title 2" - fragment@2
// 4 - nl - 2 - content - "card content 2" - fragment@2

// ATTRIBUTES
// 2 - fr - 1 - title - "page title" - Page@3
// 2 - fr - 1 - title - "card titre" - Page@3

//        chiefAttributes


// $page->fragments
// $page->cards

class FragmentTest extends TestCase
{
    /** @test */
    public function it_can_have_an_asset()
    {
        $fragment = 
    }

    /** @test */
    public function it_can_be_rendered_for_view()
    {
        // test it out -> is this more a dynamic thing???
    }

    /** @test */
    public function it_can_be_rendered_for_edit()
    {
        $this->app['view']->addNamespace('test-views', __DIR__ . '/../Fields/stubs/views');

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);
        $manager = (new ManagerFake(app(Register::class)->filterByKey('managed_model_first')->first()));

        $render = $manager->renderFormGroup(
            FormGroup::make(new Fields([
                InputField::make('input-one')->view('test-views::custom-field'),
            ]))
        );

        $this->assertStringContainsString('this is a custom field view',$render);
    }

    /** @test */
    public function it_can_be_nested()
    {
        // test it out
    }
}
