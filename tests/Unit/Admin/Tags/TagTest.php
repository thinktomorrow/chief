<?php

namespace Thinktomorrow\Chief\Tests\Unit\Admin\Tags;

use Thinktomorrow\Chief\Admin\Tags\Tag;
use Thinktomorrow\Chief\Admin\Tags\TagState;
use Thinktomorrow\Chief\Tests\TestCase;

class TagTest extends TestCase
{
    public function test_it_can_create_a_tag()
    {
        $tag = new Tag('id', TagState::online, 'intern label', ['option' => 'foobar']);


        $this->assertEquals('id', $tag->id);
        $this->assertEquals(TagState::online, $tag->getState());
        $this->assertEquals('intern label', $tag->getLabel());
        $this->assertEquals(['option' => 'foobar'], $tag->getData());
    }

    public function test_it_can_read_nested_data_with_dotted_syntax()
    {
    }

    public function test_it_can_read_localized_data()
    {
    }
}
