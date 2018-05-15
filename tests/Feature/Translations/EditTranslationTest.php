<?php

namespace Chief\Tests\Feature\Translations;

use Chief\Translations\Translation;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;
use Thinktomorrow\Squanto\Domain\Line;
use Thinktomorrow\Squanto\Domain\Page;

class EditTranslationTest extends TestCase
{
    use ChiefDatabaseTransactions;

    private $squantoPage;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        // Set locales to nl, fr for our tests
        app('config')['squanto'] = array_merge(config('squanto'),['locales' => ['nl','fr']]);

        $this->squantoPage = $this->createSquantoPage();
    }

    /** @test */
    function admin_can_view_the_edit_form()
    {
        $this->disableExceptionHandling();

        $response = $this->asDefaultAdmin()->get(route('squanto.edit', $this->squantoPage->id));
        $response->assertStatus(200);
    }

    /** @test */
    function guests_cannot_view_the_edit_form()
    {
        $response = $this->get(route('squanto.edit', $this->squantoPage->id));
        $response->assertStatus(302)->assertRedirect(route('back.login'));
    }

    /** @test */
    function editing_a_new_translation()
    {
        $this->disableExceptionHandling();

        $response = $this->asDefaultAdmin()
            ->put(route('squanto.update', $this->squantoPage->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('squanto.edit', $this->squantoPage->id));

        $this->assertCount(1, Page::all());
        $this->assertNewValues(Page::first());
    }

    /** @test */
    function only_authenticated_admin_can_edit_a_translation()
    {
        $response = $this->put(route('squanto.update', $this->squantoPage->id), $this->validParams());

        $response->assertRedirect(route('back.login'));
        $this->assertCount(1, Page::all());
        $this->assertValuesUnchanged(Page::first());
    }

    private function validParams($overrides = [])
    {
        $params = [
            'trans' => [
                'nl' => [
                    1    => 'new-entry-1',
                    2    => 'new-entry-2',
                ],
                'fr' => [
                    1    => 'nouveau-entry-1',
                    2    => 'nouveau-entry-2',
                ],
            ],
        ];

        foreach ($overrides as $key => $value){
            array_set($params,  $key, $value);
        }

        return $params;
    }

    private function assertNewValues(Page $page)
    {
        $this->assertEquals('new-entry-1', Line::findByKey('squanto-page.line-1')->getValue('nl'));
        $this->assertEquals('new-entry-2', Line::findByKey('squanto-page.line-2')->getValue('nl'));

        $this->assertEquals('nouveau-entry-1', Line::findByKey('squanto-page.line-1')->getValue('fr'));
        $this->assertEquals('nouveau-entry-2', Line::findByKey('squanto-page.line-2')->getValue('fr'));
    }

    private function assertValuesUnchanged(Page $page)
    {
        $this->assertEquals('', Line::findByKey('squanto-page.line-1')->getValue('nl'));
        $this->assertEquals('', Line::findByKey('squanto-page.line-2')->getValue('nl'));

        $this->assertEquals('', Line::findByKey('squanto-page.line-1')->getValue('fr'));
        $this->assertEquals('', Line::findByKey('squanto-page.line-2')->getValue('fr'));
    }

    /**
     * @return Page
     */
    private function createSquantoPage(): Page
    {
        $squantoPage = new Page();
        $squantoPage->label = 'squanto page';
        $squantoPage->key = 'squanto-page';
        $squantoPage->save();

        Line::make('squanto-page.line-1');
        Line::make('squanto-page.line-2');

        return $squantoPage;
    }
}