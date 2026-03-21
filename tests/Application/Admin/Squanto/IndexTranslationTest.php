<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Squanto;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Squanto\Database\DatabaseLine;

class IndexTranslationTest extends ChiefTestCase
{
    public function test_admin_can_view_the_translation_index(): void
    {
        $response = $this->asAdmin()->get(route('squanto.index'));

        $response->assertViewIs('squanto::index')
            ->assertSee('Zoek')
            ->assertStatus(200);
    }

    public function test_guest_cannot_view_the_translation_index(): void
    {
        $response = $this->get(route('squanto.index'));

        $response->assertStatus(302)->assertRedirect(route('chief.back.login'));
    }

    public function test_admin_can_search_translations(): void
    {
        DatabaseLine::create([
            'key' => 'home.hero.title',
            'values' => ['value' => [
                'nl' => 'Welkom op home',
                'en' => 'Welcome home',
            ]],
        ]);

        DatabaseLine::create([
            'key' => 'contact.hero.title',
            'values' => ['value' => [
                'nl' => 'Contact',
                'en' => 'Contact',
            ]],
        ]);

        $response = $this->asAdmin()->get(route('squanto.index').'?search=welcome');

        $response->assertStatus(200)
            ->assertSee('home.hero.title')
            ->assertSee('<strong>Welcome</strong> home', false)
            ->assertSee('#homeherotitle')
            ->assertDontSee('contact.hero.title');
    }

    public function test_search_term_stays_active_until_reset(): void
    {
        DatabaseLine::create([
            'key' => 'home.hero.title',
            'values' => ['value' => [
                'en' => 'Welcome home',
            ]],
        ]);

        $this->asAdmin()->get(route('squanto.index').'?search=welcome')->assertStatus(200);

        $response = $this->asAdmin()->get(route('squanto.index'));

        $response->assertStatus(200)
            ->assertViewIs('squanto::search')
            ->assertSee('<strong>Welcome</strong> home', false);
    }

    public function test_reset_clears_the_active_search_term(): void
    {
        DatabaseLine::create([
            'key' => 'home.hero.title',
            'values' => ['value' => [
                'en' => 'Welcome home',
            ]],
        ]);

        $this->asAdmin()->get(route('squanto.index').'?search=welcome')->assertStatus(200);

        $response = $this->asAdmin()->get(route('squanto.index', ['reset' => 1]));

        $response->assertRedirect(route('squanto.index'));

        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertViewIs('squanto::index')
            ->assertDontSee('home.hero.title');
    }

    public function test_empty_search_term_resets_the_active_search(): void
    {
        DatabaseLine::create([
            'key' => 'home.hero.title',
            'values' => ['value' => [
                'en' => 'Welcome home',
            ]],
        ]);

        $this->asAdmin()->get(route('squanto.index').'?search=welcome')->assertStatus(200);

        $response = $this->asAdmin()->get(route('squanto.index').'?search=');

        $response->assertRedirect(route('squanto.index'));

        $this->followRedirects($response)
            ->assertStatus(200)
            ->assertViewIs('squanto::index')
            ->assertDontSee('home.hero.title');
    }
}
