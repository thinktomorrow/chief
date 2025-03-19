<?php

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Thinktomorrow\Chief\Site\Urls\Application\RedirectUrl;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\UrlRecordNotFound;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UrlRecordTest extends ChiefTestCase
{
    public function test_it_can_find_a_matching_slug()
    {
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('foo/bar', 'nl')->id);
    }

    public function test_it_can_find_a_localized_slug_when_locale_matches()
    {
        UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('foo/bar', 'nl')->id);
    }

    public function test_it_ignores_the_outer_slashes_from_the_slug_argument()
    {
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('/foo/bar/', 'nl')->id);
    }

    public function test_it_throws_exception_when_locale_does_not_match()
    {
        $this->expectException(UrlRecordNotFound::class);

        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        UrlRecord::findBySlug('foo/bar', 'en');
    }

    public function test_it_throws_exception_when_no_record_was_found()
    {
        $this->expectException(UrlRecordNotFound::class);

        UrlRecord::findBySlug('xxx', 'nl');
    }

    public function test_when_adding_new_url_it_sets_existing_url_as_redirect()
    {
        $existing = UrlRecord::create(['locale' => 'fr', 'slug' => 'foo/bar', 'model_type' => 'foobar', 'model_id' => '1']);
        $new = app(RedirectUrl::class)->handle($existing, 'foo/bare');

        $this->assertEquals($new->id, UrlRecord::findBySlug('foo/bare', 'fr')->id);

        $this->assertTrue($existing->fresh()->isRedirect());
        $this->assertEquals($new->id, $existing->fresh()->getRedirectTo()->id);
    }
}
