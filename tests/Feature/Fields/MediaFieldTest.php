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

        $this->assertEquals('files[media][nl]', $mediafield->translateName('nl'));
        $this->assertEquals('files[media]', $mediafield->name());
    }

}
