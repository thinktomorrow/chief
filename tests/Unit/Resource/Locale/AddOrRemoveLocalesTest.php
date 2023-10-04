<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\Locale;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Locale\Actions\AddLocale;
use Thinktomorrow\Chief\Locale\Actions\RemoveLocale;
use Thinktomorrow\Chief\Locale\Actions\SyncLocales;
use Thinktomorrow\Chief\Locale\Events\LocalesUpdated;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class AddOrRemoveLocalesTest extends ChiefTestCase
{
    public function test_it_add_locales()
    {
        Event::fake();

        $page = $this->setUpAndCreateArticle();
        $resource = new LocaleRepositoryStub();

        app(AddLocale::class)->handle($resource, $page, ['nl', 'fr']);

        $this->assertEquals(['nl', 'fr'], $page->refresh()->locales);
        $this->assertEquals(['nl', 'fr'], $resource->getLocales($page));

        Event::assertDispatched(function (LocalesUpdated $event) use ($page) {
            return $event->modelReference == $page->modelReference() &&
                $event->newState == ['nl', 'fr'] &&
                $event->previousState == [];
        });
    }

    public function test_it_remove_locales()
    {
        Event::fake();

        $page = $this->setUpAndCreateArticle();
        $resource = new LocaleRepositoryStub();

        $page->locales = ['nl', 'fr'];
        $page->save();

        app(RemoveLocale::class)->handle($resource, $page, ['nl']);

        $this->assertEquals(['fr'], $page->refresh()->locales);
        $this->assertEquals(['fr'], $resource->getLocales($page));

        Event::assertDispatched(function (LocalesUpdated $event) use ($page) {
            return $event->modelReference == $page->modelReference() &&
                $event->newState == ['fr'] &&
                $event->previousState == ['nl', 'fr'];
        });
    }

    public function test_it_sync_locales()
    {
        Event::fake();

        $page = $this->setUpAndCreateArticle();
        $resource = new LocaleRepositoryStub();

        app(SyncLocales::class)->handle($resource, $page, ['nl', 'fr', 'en']);

        $this->assertEquals(['nl', 'fr', 'en'], $page->refresh()->locales);
        $this->assertEquals(['nl', 'fr', 'en'], $resource->getLocales($page));

        Event::assertDispatched(function (LocalesUpdated $event) use ($page) {
            return $event->modelReference == $page->modelReference() &&
                $event->newState == ['nl', 'fr', 'en'] &&
                $event->previousState == [];
        });

        app(SyncLocales::class)->handle($resource, $page, ['nl']);

        $this->assertEquals(['nl'], $page->refresh()->locales);
        $this->assertEquals(['nl'], $resource->getLocales($page));

        Event::assertDispatched(function (LocalesUpdated $event) use ($page) {
            return $event->modelReference == $page->modelReference() &&
                $event->newState == ['nl'] &&
                $event->previousState == ['nl', 'fr', 'en'];
        });
    }
}
