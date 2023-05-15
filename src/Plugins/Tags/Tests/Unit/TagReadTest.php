<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Tests\Unit;

use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models\DefaultTagRead;
use Thinktomorrow\Chief\Plugins\Tags\Tests\Infrastructure\TaggableStub;
use Thinktomorrow\Chief\Tests\TestCase;

class TagReadTest extends TestCase
{
    public function test_it_can_create_a_tag()
    {
        $tagRead = $this->getInstance();

        $this->assertEquals('1', $tagRead->getTagId());
        $this->assertEquals('2', $tagRead->getTagGroupId());
        $this->assertEquals('internal label', $tagRead->getLabel());
        $this->assertEquals('#333333', $tagRead->getColor());
        $this->assertEquals(5, $tagRead->getUsages());
    }

    public function test_it_can_read_data()
    {
        $tagRead = $this->getInstance(['data' => ['baz' => ['bad' => 'foobar']]]);

        $this->assertEquals(['bad' => 'foobar'], $tagRead->getData('baz'));
    }

    public function test_it_can_read_nested_data_with_dotted_syntax()
    {
        $tagRead = $this->getInstance(['data' => ['baz' => ['bad' => 'foobar']]]);

        $this->assertEquals('foobar', $tagRead->getData('baz.bad'));
        $this->assertEquals('foobar', $tagRead->getData('baz', 'bad'));
    }

    public function test_it_can_return_fallback_data()
    {
        $tagRead = $this->getInstance(['data' => ['baz' => ['bad' => 'foobar']]]);

        $this->assertEquals('FALLBACK', $tagRead->getData('unknown', null, 'FALLBACK'));
    }

    private function getInstance(array $values = []): TagRead
    {
        return DefaultTagRead::fromMappedData(array_merge([
            'id' => '1',
            'taggroup_id' => '2',
            'label' => 'internal label',
            'color' => '#333333',
            'owner_references' => collect([
                (object)[
                    'owner_type' => TaggableStub::class,
                    'owner_id' => '1',
                ],
                (object)[
                    'owner_type' => TaggableStub::class,
                    'owner_id' => '2',
                ],
                (object)[
                    'owner_type' => TaggableStub::class,
                    'owner_id' => '3',
                ],
                (object)[
                    'owner_type' => TaggableStub::class,
                    'owner_id' => '4',
                ],
                (object)[
                    'owner_type' => TaggableStub::class,
                    'owner_id' => '5',
                ],
            ]),
            'data' => [],
        ], $values));
    }
//
//    public function test_it_can_read_localized_data()
//    {
//        $tag = new Tag(TagId::fromString('xxx'), TagGroupId::fromString('yyy'), 'intern label', ['option' => ['foo' => 'foobar']]);
//
//        $this->assertEquals(['foo' => 'foobar'], $tag->getData('option'));
//    }
}
