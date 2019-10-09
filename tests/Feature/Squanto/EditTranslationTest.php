<?php

namespace Thinktomorrow\Chief\Tests\Feature\Squanto;

use Illuminate\Support\Arr;
use Thinktomorrow\Squanto\Domain\Line;
use Thinktomorrow\Squanto\Domain\Page;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;

class EditTranslationTest extends TestCase
{
    use ChiefDatabaseTransactions;

    private $squantoPage;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        // Set locales to nl, fr for our tests
        app('config')['squanto'] = array_merge(config('squanto'), ['locales' => ['nl', 'fr']]);

        $this->squantoPage = $this->createSquantoPage();
    }

    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $response = $this->asAdmin()->get(route('squanto.edit', $this->squantoPage->id));
        $response->assertViewIs('squanto::edit')
                 ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        $response = $this->get(route('squanto.edit', $this->squantoPage->id));
        $response->assertStatus(302)->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    public function editing_a_new_translation()
    {
        $response = $this->asAdmin()
            ->put(route('squanto.update', $this->squantoPage->id), $this->validParams());
        dump($response);
        $response->assertStatus(302);
        $response->assertRedirect(route('squanto.edit', $this->squantoPage->id));

        $this->assertCount(1, Page::all());
        $this->assertNewValues(Page::first());
    }

    /** @test */
    public function non_authenticated_admin_cannot_edit_a_translation()
    {
        $response = $this->put(route('squanto.update', $this->squantoPage->id), $this->validParams());

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(1, Page::all());
        $this->assertValuesUnchanged(Page::first());
    }

    private function validParams($overrides = [])
    {
        $params = [
            'trans' => [
                'nl' => [
                    1 => 'new-entry-1',
                    2 => 'new-entry-2',
                ],
                'fr' => [
                    1 => 'nouveau-entry-1',
                    2 => 'nouveau-entry-2',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
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
