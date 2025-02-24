<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Domain;

use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class ForwardFragmentPropertiesTest extends ChiefTestCase
{
    public function test_it_forwards_calls_to_fragmentmodel()
    {
        $snippet = new SnippetStub;
        $snippet->setFragmentModel(new FragmentModel(['title' => 'foobar']));

        $this->assertEquals($snippet->getFragmentModel()->title, $snippet->title);
        $this->assertEquals('foobar', $snippet->title);
    }

    public function test_non_existing_fragmentmodel_property_results_in_null()
    {
        $snippet = FragmentTestHelpers::createFragment(SnippetStub::class);

        $this->assertNull($snippet->unknown_value);
    }
}
