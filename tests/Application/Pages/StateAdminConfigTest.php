<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticleState;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticleWithStateAdminConfig;

class StateAdminConfigTest extends ChiefTestCase
{
    private $page;

    public function setUp(): void
    {
        parent::setUp();

        ArticleWithStateAdminConfig::migrateUp();
        chiefRegister()->resource(ArticleWithStateAdminConfig::class, PageManager::class);
        $this->page = ArticleWithStateAdminConfig::create(['article_state' => 'offline']);
    }

    public function test_it_can_change_state()
    {
        $this->asAdmin()
            ->put($this->manager($this->page)->route('state-update', $this->page, 'article_state', 'publish'));

        $this->assertEquals(ArticleState::online, $this->page->refresh()->getState('article_state'));
    }

    public function test_it_can_submit_extra_fields_when_setting_state()
    {
        $this->asAdmin()->put($this->manager($this->page)->route('state-update', $this->page, 'article_state', 'publish'));
        $this->asAdmin()->put($this->manager($this->page)->route('state-update', $this->page, 'article_state', 'draft'), [
            'draft_note' => 'foobar',
        ]);

        $this->assertEquals(ArticleState::offline, $this->page->refresh()->getState('article_state'));
        $this->assertEquals('foobar', $this->page->refresh()->draft_note);
    }

    public function test_it_can_validate_extra_fields()
    {
        $this->asAdmin()->put($this->manager($this->page)->route('state-update', $this->page, 'article_state', 'publish'));
        $response = $this->asAdmin()->put($this->manager($this->page)->route('state-update', $this->page, 'article_state', 'draft'), []);

        $response
            ->assertSessionHasErrors('draft_note')
            ->assertStatus(302);

        // State has not changed because field was required
        $this->assertEquals(ArticleState::online, $this->page->refresh()->getState('article_state'));
    }
}
