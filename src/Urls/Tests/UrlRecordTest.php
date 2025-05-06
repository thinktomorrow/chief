<?php

namespace Thinktomorrow\Chief\Urls\Tests;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\CreateRedirectTo;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\Exceptions\UrlRecordNotFound;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class UrlRecordTest extends ChiefTestCase
{
    public function test_it_can_find_a_matching_slug()
    {
        $record = UrlRecord::create(['site' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('foo/bar', 'nl')->id);
    }

    public function test_it_can_find_a_localized_slug_when_site_matches()
    {
        UrlRecord::create(['site' => 'en', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);
        $record = UrlRecord::create(['site' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('foo/bar', 'nl')->id);
    }

    public function test_it_ignores_the_outer_slashes_from_the_slug_argument()
    {
        $record = UrlRecord::create(['site' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('/foo/bar/', 'nl')->id);
    }

    public function test_it_throws_exception_when_site_does_not_match()
    {
        $this->expectException(UrlRecordNotFound::class);

        UrlRecord::create(['site' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        UrlRecord::findBySlug('foo/bar', 'en');
    }

    public function test_it_throws_exception_when_no_record_was_found()
    {
        $this->expectException(UrlRecordNotFound::class);

        UrlRecord::findBySlug('xxx', 'nl');
    }

    public function test_when_adding_new_url_it_sets_existing_url_as_redirect()
    {
        $model = $this->setUpAndCreateArticle();
        $existingId = app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'en', 'foobar', 'online'));
        $redirectId = app(RedirectApplication::class)->createRedirectTo(new CreateRedirectTo($existingId, 'foo/bare'));

        $this->assertEquals($redirectId, UrlRecord::findBySlug('foo/bare', 'en')->id);

        $this->assertTrue(UrlRecord::find($redirectId)->isRedirect());
        $this->assertEquals($existingId, UrlRecord::find($redirectId)->redirect_id);
        $this->assertFalse(UrlRecord::find($existingId)->isRedirect());
    }
}
