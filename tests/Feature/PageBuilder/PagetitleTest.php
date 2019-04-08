<?php

namespace Thinktomorrow\Chief\Tests\Feature\PageBuilder;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Modules\PagetitleModule;
use Thinktomorrow\Chief\Modules\TextModule;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class PagetitleTest extends TestCase
{
    use PageBuildFormParams;

    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
        config()->set('app.fallback_locale', 'nl');

        app(Register::class)->register('articles', PageManager::class, ArticlePageFake::class);

        // Create a dummy page up front based on the expected validPageParams
        $this->page = ArticlePageFake::create([
            'title:nl' => 'new title',
            'slug:nl' => 'new-slug',
            'title:en' => 'nouveau title',
            'slug:en' => 'nouveau-slug',
        ]);

        // For our project context we expect the page detail route to be known
        Route::get('pages/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function it_can_add_a_pagetitle_module()
    {
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles', $this->page->id]), $this->validPageParams([
                'sections.text.new' => [
                    [
                        'slug' => 'text-1',
                        'type' => 'pagetitle',
                        'trans' => [
                            'nl' => [
                                'content' => 'new title',
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertInstanceOf(Module::class, $this->page->children()->first());
        $this->assertInstanceOf(PagetitleModule::class, $this->page->children()->first());
        $this->assertEquals('new title', $this->page->children()->first()->content);
    }

    /** @test */
    public function it_can_replace_a_pagetitle_module()
    {
        // Add first text module
        $module = PagetitleModule::create(['slug' => 'eerste-pagetitle']);
        $this->page->adoptChild($module, ['sort' => 0]);

        // Replace text module content
        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [
                    [
                        'id'    => $module->flatReference()->get(),
                        'trans' => [
                            'nl' => [
                                'content' => 'replaced title',
                            ]
                        ]
                    ]
                ]
            ]));

        $this->assertCount(1, $this->page->children());
        $this->assertEquals('replaced title', $this->page->freshChildren()->first()->content);
    }

    /** @test */
    public function it_removes_a_pagetitle_module_when_its_completely_empty()
    {
        // Add first text module
        $module = TextModule::create(['slug' => 'eerste-text']);
        $this->page->adoptChild($module, ['sort' => 0]);

        $this->asAdmin()
            ->put(route('chief.back.managers.update', ['articles', $this->page->id]), $this->validPageParams([
                'sections.text.new'     => [],
                'sections.text.replace' => [
                    [
                        'id'    => $module->flatReference()->get(),
                        'trans' => [
                            'nl' => [
                                'content' => '  ',
                            ]
                        ]
                    ]
                ],
            ]));

        $this->assertCount(0, $this->page->children());

        // Module is also deleted
        $this->assertNull(Module::find($module->id));
        $this->assertEquals($module->id, Module::withTrashed()->find($module->id)->id);
    }

    /** @test */
    public function it_displays_pagetitle()
    {
        $module = TextModule::create(['slug' => 'eerste-text', 'content:nl' => 'eerste titel']);
        $this->page->adoptChild($module, ['sort' => 0]);

        $this->assertEquals('eerste titel', $this->page->renderChildren());
    }
}
