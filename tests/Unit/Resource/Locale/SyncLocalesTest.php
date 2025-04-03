<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\Locale;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Sites\Actions\AddSite;
use Thinktomorrow\Chief\Sites\Actions\RemoveLocale;
use Thinktomorrow\Chief\Sites\Actions\SaveSiteLocales;
use Thinktomorrow\Chief\Sites\Events\ModelSitesUpdated;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class SyncLocalesTest extends ChiefTestCase
{
    public function test_it_sync_locales()
    {
        Event::fake();

        $page = $this->setUpAndCreateArticle();
        $resource = new LocaleRepositoryStub;

        app(SaveSiteLocales::class)->handle($page, ['nl', 'fr', 'en']);

        $this->assertEquals(['nl', 'fr', 'en'], $page->refresh()->locales);
        $this->assertEquals(['nl', 'fr', 'en'], $resource->getLocales($page));

        Event::assertDispatched(function (ModelSitesUpdated $event) use ($page) {
            return $event->modelReference == $page->modelReference() &&
                $event->newState == ['nl', 'fr', 'en'] &&
                $event->previousState == [];
        });

        app(SaveSiteLocales::class)->handle($page, ['nl']);

        $this->assertEquals(['nl'], $page->refresh()->locales);
        $this->assertEquals(['nl'], $resource->getLocales($page));

        Event::assertDispatched(function (ModelSitesUpdated $event) use ($page) {
            return $event->modelReference == $page->modelReference() &&
                $event->newState == ['nl'] &&
                $event->previousState == ['nl', 'fr', 'en'];
        });
    }

    public function test_it_sync_locales_and_maintains_sorting_like_config()
    {
        Event::fake();

        $page = $this->setUpAndCreateArticle();
        $resource = new LocaleRepositoryStub;

        config()->set('chief.locales.admin', ['fr', 'nl', 'en']);

        app(SaveSiteLocales::class)->handle($page, ['nl', 'fr', 'en']);

        $this->assertEquals(['fr', 'nl', 'en'], $page->refresh()->locales);
        $this->assertEquals(['fr', 'nl', 'en'], $resource->getLocales($page));

        Event::assertDispatched(function (ModelSitesUpdated $event) use ($page) {
            return $event->modelReference == $page->modelReference() &&
                $event->newState == ['fr', 'nl', 'en'] &&
                $event->previousState == [];
        });
    }

    public function test_it_add_locales()
    {
        Event::fake();

        $page = $this->setUpAndCreateArticle();
        $resource = new LocaleRepositoryStub;

        app(AddSite::class)->handle($resource, $page, ['nl', 'fr']);

        $this->assertEquals(['nl', 'fr'], $page->refresh()->locales);
        $this->assertEquals(['nl', 'fr'], $resource->getLocales($page));

        Event::assertDispatched(function (ModelSitesUpdated $event) use ($page) {
            return $event->modelReference == $page->modelReference() &&
                $event->newState == ['nl', 'fr'] &&
                $event->previousState == [];
        });
    }

    public function test_it_remove_locales()
    {
        Event::fake();

        $page = $this->setUpAndCreateArticle();
        $resource = new LocaleRepositoryStub;

        $page->locales = ['nl', 'fr'];
        $page->save();

        app(RemoveLocale::class)->handle($resource, $page, ['nl']);

        $this->assertEquals(['fr'], $page->refresh()->locales);
        $this->assertEquals(['fr'], $resource->getLocales($page));

        Event::assertDispatched(function (ModelSitesUpdated $event) use ($page) {
            return $event->modelReference == $page->modelReference() &&
                $event->newState == ['fr'] &&
                $event->previousState == ['nl', 'fr'];
        });
    }
}
