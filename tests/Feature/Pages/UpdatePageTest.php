<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Common\Relations\Relation;
use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class UpdatePageTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->app['config']->set('thinktomorrow.chief.collections.pages', [
            'singles' => Single::class,
            'articles' => ArticlePageFake::class,
        ]);

        $this->page = app(CreatePage::class)->handle('articles', $this->validPageParams()['trans'], [], [], []);
    }

    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $this->disableExceptionHandling();

        $this->asAdmin()->get(route('chief.back.pages.edit', $this->page->id))
                               ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        $this->get(route('chief.back.pages.edit', $this->page->id))
             ->assertStatus(302)
             ->assertRedirect(route('chief.back.login'));

        $this->assertNewPageValues($this->page->fresh());
    }

    /** @test */
    public function it_can_edit_a_page()
    {
        $page = factory(Page::class)->create();

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams());

        $this->assertUpdatedPageValues($page->fresh());
    }

    /** @test */
    public function it_can_update_the_page_relations()
    {
        $page = factory(Page::class)->create();
        $otherPage = factory(Page::class)->create();

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams([
                'relations' => [
                    $otherPage->flatReference()->get()
                ]
            ]));

        $this->assertCount(1, $page->fresh()->children());
        $this->assertEquals($otherPage->id, $page->fresh()->children()->first()->id);
    }

    /** @test */
    public function when_updating_page_title_is_required()
    {
        $this->assertValidation(new Page(), 'trans.nl.title', $this->validUpdatePageParams(['trans.nl.title' => '']),
            route('chief.back.pages.index', 'singles'),
            route('chief.back.pages.update', $this->page->id),
            1, 'put'
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $otherPage = factory(Page::class)->create([
            'trans.nl.title'  => 'titel nl',
            'trans.nl.slug'   => 'foobarnl'
        ]);

        $this->assertCount(2, Page::all());

        $response = $this->asDefaultAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validUpdatePageParams([
                'trans.nl.title'  => 'foobarnl',
                'trans.en.title'  => 'foobaren',
            ])
            );

        $response->assertStatus(302);

        $this->assertNotNull($otherPage->{'slug:nl'});
        $this->assertNotEquals($this->page->{'slug:nl'}, $otherPage->{'slug:nl'});
    }

    /** @test */
    public function only_nl_is_required()
    {
        $response = $this->asAdmin()
            ->put(route('chief.back.pages.update', $this->page->id), $this->validUpdatePageParams([
                'trans.en'  => [
                    'title' => '',
                    'slug' => '',
                ],
            ])
        );
        $response->assertStatus(302);

        $this->assertNull($this->page->fresh()->getTranslation('en'));
    }
}
