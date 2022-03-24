<?php

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\UrlStatus;
use Thinktomorrow\Chief\Site\Urls\UrlRecordNotFound;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPutOnline;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelPutOffline;

class UrlRecordTest extends ChiefTestCase
{
    /** @test */
    public function it_can_find_a_matching_slug()
    {
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('foo/bar', 'nl')->id);
    }

    /** @test */
    public function it_can_find_a_localized_slug_when_locale_matches()
    {
        UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('foo/bar', 'nl')->id);
    }

    /** @test */
    public function it_ignores_the_outer_slashes_from_the_slug_argument()
    {
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        $this->assertEquals($record->id, UrlRecord::findBySlug('/foo/bar/', 'nl')->id);
    }

    /** @test */
    public function it_throws_exception_when_locale_does_not_match()
    {
        $this->expectException(UrlRecordNotFound::class);

        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => '']);

        UrlRecord::findBySlug('foo/bar', 'en');
    }

    /** @test */
    public function it_throws_exception_when_no_record_was_found()
    {
        $this->expectException(UrlRecordNotFound::class);

        UrlRecord::findBySlug('xxx', 'nl');
    }

    /** @test */
    public function when_adding_new_url_it_sets_existing_url_as_redirect()
    {
        $existing = UrlRecord::create(['locale' => 'fr', 'slug' => 'foo/bar', 'model_type' => 'foobar', 'model_id' => '1']);
        $new = $existing->replaceAndRedirect([
            'locale' => 'nl',
            'slug' => 'foo/bar',
        ]);

        $this->assertEquals($new->id, UrlRecord::findBySlug('foo/bar', 'nl')->id);

        $this->assertTrue($existing->fresh()->isRedirect());
        $this->assertEquals($new->id, $existing->fresh()->redirectTo()->id);
    }

    /** @test */
    public function status_is_put_offline_when_model_is_drafted()
    {
        $page = $this->setupAndCreateArticle();

        $nlUrlRecord = UrlRecord::create(['status' => UrlStatus::online->value, 'locale' => 'nl', 'slug' => 'foo', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id, 'internal_label' => null]);
        $enUrlRecord = UrlRecord::create(['status' => UrlStatus::online->value, 'locale' => 'en', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id,  'internal_label' => null]);

        event(new ManagedModelPutOffline($page->modelReference()));

        $this->assertEquals(UrlStatus::offline->value, $nlUrlRecord->fresh()->status);
        $this->assertEquals(UrlStatus::offline->value, $enUrlRecord->fresh()->status);
    }

    /** @test */
    public function status_is_put_online_when_model_is_published()
    {
        $page = $this->setupAndCreateArticle();

        $nlUrlRecord = UrlRecord::create(['status' => UrlStatus::offline->value, 'locale' => 'nl', 'slug' => 'foo', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id, 'internal_label' => null]);
        $enUrlRecord = UrlRecord::create(['status' => UrlStatus::offline->value, 'locale' => 'en', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id,  'internal_label' => null]);

        event(new ManagedModelPutOnline($page->modelReference()));

        $this->assertEquals(UrlStatus::online->value, $nlUrlRecord->fresh()->status);
        $this->assertEquals(UrlStatus::online->value, $enUrlRecord->fresh()->status);
    }

    /** @test */
    public function it_updates_internal_label_when_model_title_is_updated()
    {
        $this->disableExceptionHandling();
        $page = $this->setupAndCreateArticle(['custom' => 'foobar']);

        $nlUrlRecord = UrlRecord::create(['status' => UrlStatus::online->value, 'locale' => 'nl', 'slug' => 'foo', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id, 'internal_label' => null]);
        $enUrlRecord = UrlRecord::create(['status' => UrlStatus::online->value, 'locale' => 'en', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id,  'internal_label' => null]);

        event(new ManagedModelUpdated($page->modelReference()));

        $this->assertEquals('foobar', $nlUrlRecord->fresh()->internal_label);
        $this->assertEquals('foobar', $enUrlRecord->fresh()->internal_label);
    }
}
