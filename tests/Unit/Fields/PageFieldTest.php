<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Fields\Types\PageField;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
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
    public function pagefield_can_get_options()
    {
        $page = Single::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo', 'model_type' => $page->morphKey(), 'model_id' => $page->id]);

        $pagefield = PageField::make('foobar')->options();

        $this->assertCount(2, $pagefield->getOptions()[0]['values']);
    }

    /** @test */
    public function pagefield_can_exclude_self()
    {
        $page = Single::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo', 'model_type' => $page->morphKey(), 'model_id' => $page->id]);

        $pagefield = PageField::make('foobar')->exclude($this->page)->options();

        $this->assertCount(1, $pagefield->getOptions()[0]['values']);

    }

    /** @test */
    public function pagelink_trait_converts_flatreference_to_url()
    {
        $single = SingleFakeWithPageField::create(['buttonlink' => $this->page->flatreference()->get()]);

        $this->assertEquals('http://localhost/foo/bar', $single->buttonlink);
    }
}
