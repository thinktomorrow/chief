<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Fields\Types\PageField;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\SingleFakeWithPageField;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFakeWithPageField;

class PageFieldTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        SingleFakeWithPageField::migrateUp();

        app(Register::class)->register(ManagerFakeWithPageField::class, Single::class);
        $this->page = Single::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $this->page->morphKey(), 'model_id' => $this->page->id]);
    }

    /** @test */
    public function pagefield_can_automatically_set_all_online_pages_as_options()
    {
        // Offline page is not added
        Single::create();

        $pagefield = PageField::make('foobar')->onlinePagesAsOptions();

        $this->assertCount(1, $pagefield->getOptions()[0]['values']);
    }

    /** @test */
    public function pagefield_can_exclude_a_page()
    {
        $page = Single::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo', 'model_type' => $page->morphKey(), 'model_id' => $page->id]);

        $pagefield = PageField::make('foobar')->onlinePagesAsOptions($this->page);

        $this->assertCount(1, $pagefield->getOptions()[0]['values']);
    }

    /** @test */
    public function pagefield_can_whitelist_certain_page_types()
    {
        ManagedModelFakeFirst::migrateUp();
        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);
        $otherPage = ManagedModelFakeFirst::create(['current_state' => PageState::PUBLISHED]);
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foobaz', 'model_type' => get_class($otherPage), 'model_id' => $otherPage->id]);

        $pagefield = PageField::make('foobar')->onlinePagesAsOptions(null, [$otherPage->managedModelKey()]);

        $this->assertCount(1, $pagefield->getOptions()[0]['values']);
        $this->assertEquals($otherPage->flatReference()->get(), $pagefield->getOptions()[0]['values'][0]['id']);
    }

    /** @test */
    public function page_reference_trait_converts_flatreference_to_model()
    {
        $single = SingleFakeWithPageField::create(['page' => $this->page->flatreference()->get()]);

        $this->assertInstanceOf(get_class($this->page), $single->page);
        $this->assertEquals($this->page->id, $single->page->id);
    }
}
