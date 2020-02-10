<?php

namespace Thinktomorrow\Chief\Tests\Feature\Fields;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Fields\Types\FileField;
use Thinktomorrow\Chief\Fields\Types\ImageField;

class MediaFieldTest extends TestCase
{
    /** @test */
    public function it_prepends_name_with_files()
    {
        $this->assertEquals('files[media][nl]', FileField::make('media')->getName('nl'));
        $this->assertEquals('images[media][nl]', ImageField::make('media')->getName('nl'));
    }

    /** @test */
    public function media_assets_are_always_localized()
    {
        $imageField = ImageField::make('media');

        $this->assertTrue($imageField->isLocalized());
        $this->assertCount(1, $imageField->getLocales());
    }

}
