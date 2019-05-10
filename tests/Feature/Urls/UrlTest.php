<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Urls\UrlRecordNotFound;

class UrlTest extends TestCase
{
    /** @test */
    function it_can_find_a_matching_slug()
    {
        $record = UrlRecord::create(['locale' => null, 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('foo/bar', 'nl')->id);
    }

    /** @test */
    function it_can_find_a_localized_slug_when_locale_matches()
    {
        UrlRecord::create(['locale' => null, 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);
        UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('foo/bar', 'nl')->id);
    }

    /** @test */
    function it_will_get_the_non_localized_record_when_locale_does_not_match()
    {
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);
        $record = UrlRecord::create(['locale' => null, 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('foo/bar', 'en')->id);
    }

    /** @test */
    function it_ignores_the_outer_slashes_from_the_slug_argument()
    {
        $record = UrlRecord::create(['locale' => null, 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('/foo/bar/', 'nl')->id);
    }

    /** @test */
    function it_throws_exception_when_no_record_was_found()
    {
        $this->expectException(UrlRecordNotFound::class);

        UrlRecord::findBySlug('xxx', 'nl');
    }

    /** @test */
    function when_adding_new_url_it_sets_existing_url_as_redirect()
    {
        $existing = UrlRecord::create(['locale' => null, 'slug' => 'foo/bar', 'model_type' => 'foobar', 'model_id' => '1']);
        $new = $existing->replace([
            'locale' => 'nl',
            'slug' => 'foo/bar',
        ]);

        $this->assertEquals($new->id, UrlRecord::findBySlug('foo/bar', 'nl')->id);

        $this->assertTrue($existing->fresh()->isRedirect());
        $this->assertEquals($new->id, $existing->fresh()->redirectTo()->id);
    }
}
