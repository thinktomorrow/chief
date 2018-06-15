<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Application\CreatePage;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;

class UpdatePageTest extends TestCase
{
    use PageFormParams;

    private $page;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->page = app(CreatePage::class)->handle('custom-collection', $this->validPageParams()['trans'], [], [], []);
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
                    $otherPage->getRelationId()
                ]
            ]));

        $this->assertCount(1, $page->children());
        $this->assertEquals($otherPage->id, $page->children()->first()->id);
    }

    /** @test */
    public function when_updating_page_title_is_required()
    {
        $this->assertValidation(new Page(), 'trans.nl.title', $this->validPageParams(['trans.nl.title' => '']),
            route('chief.back.pages.index', 'statics'),
            route('chief.back.pages.update', $this->page->id)
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $otherPage = factory(Page::class)->create([
            'trans.nl.title'  => 'titel nl',
            'trans.nl.slug'   => 'foobarnl'
        ]);

        $this->assertCount(1, Page::all());

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
}
