<?php

namespace Thinktomorrow\Chief\Tests\Unit\Admin\Tags;

use Thinktomorrow\Chief\Admin\Tags\Tag;
use Thinktomorrow\Chief\Admin\Tags\TagGroupId;
use Thinktomorrow\Chief\Admin\Tags\TagId;
use Thinktomorrow\Chief\Tests\TestCase;

class TagTest extends TestCase
{
    public function test_it_can_create_a_tag()
    {
        $tag = new Tag($tagId = TagId::fromString('xxx'), $tagGroupId = TagGroupId::fromString('yyy'), 'intern label', ['option' => 'foobar']);


        $this->assertEquals($tagId, $tag->tagId);
        $this->assertEquals($tagGroupId, $tag->getTagGroupId());
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
