<?php

namespace Thinktomorrow\Chief\Tests\Feature\Fields;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Fields\Types\MediaField;

class MediaFieldTest extends TestCase
{
    /** @test */
    public function it_prepends_name_with_files()
    {
        $mediafield = MediaField::make('media');

        $this->assertEquals('files[media][nl]', $mediafield->getName('nl'));
//        $this->assertEquals('files[media]', $mediafield->getName());
    }

    /** @test */
    public function media_assets_are_always_localized()
    {
        $mediafield = MediaField::make('media');

        $this->assertTrue($mediafield->isLocalized());
        $this->assertCount(1, $mediafield->getLocales());
    }

}
