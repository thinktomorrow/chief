<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

class RoleVisibilityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useProjectGuides($this->fixture('roles'));
    }

    public function test_a_guide_without_roles_or_with_an_empty_list_is_visible_to_every_admin(): void
    {
        $this->asAuthor()->get('/admin/docs/iedereen')->assertOk();
        $this->asAuthor()->get('/admin/docs/lege-rollen')->assertOk();
    }

    public function test_an_admin_with_a_listed_role_sees_the_guide(): void
    {
        $this->asAdmin()->get('/admin/docs/admins')->assertOk();
    }

    public function test_an_admin_without_a_listed_role_gets_a_404(): void
    {
        $this->asAuthor()->get('/admin/docs/admins')->assertNotFound();
    }

    public function test_roles_match_any_of_the_listed_roles(): void
    {
        $this->asAuthor()->get('/admin/docs/redactie')->assertOk();
    }

    public function test_an_unknown_role_matches_nobody(): void
    {
        $this->asAdmin()->get('/admin/docs/onbekende-rol')->assertNotFound();
    }

    public function test_a_single_role_written_as_text_restricts_the_guide(): void
    {
        $this->asAuthor()->get('/admin/docs/rol-als-tekst')->assertNotFound();
    }

    public function test_a_single_role_written_as_text_is_matched(): void
    {
        $this->asAdmin()->get('/admin/docs/rol-als-tekst')->assertOk();
    }

    public function test_malformed_roles_match_nobody(): void
    {
        $this->asAdmin()->get('/admin/docs/ongeldige-rollen')->assertNotFound();
    }

    public function test_a_developer_gets_no_bypass_on_restricted_guides(): void
    {
        $this->asDeveloper()->get('/admin/docs/admins')->assertNotFound();
        $this->asDeveloper()->get('/admin/docs/iedereen')->assertOk();
    }

    public function test_a_section_restriction_applies_to_every_guide_beneath_it(): void
    {
        $this->asAuthor()->get('/admin/docs/beheer')->assertNotFound();
        $this->asAuthor()->get('/admin/docs/beheer/gebruikers')->assertNotFound();
        $this->asAuthor()->get('/admin/docs/verhuisd')->assertNotFound();
    }

    public function test_a_child_cannot_widen_its_section_restriction(): void
    {
        $this->asAuthor()->get('/admin/docs/beheer/verbreed')->assertNotFound();
    }

    public function test_a_child_can_narrow_its_section_restriction(): void
    {
        $this->asAdmin()->get('/admin/docs/beheer/gebruikers')->assertOk();
        $this->asAdmin()->get('/admin/docs/beheer/verbreed')->assertNotFound();
    }

    public function test_a_restricted_subtree_is_absent_from_the_visible_tree(): void
    {
        $this->actingAs($this->author(), 'chief');
        $tree = $this->guideTree();

        $this->assertNull($tree->find('beheer'));
        $this->assertNull($tree->find('beheer/gebruikers'));
        $this->assertNull($tree->find('verhuisd'));
        $this->assertNotContains('beheer', array_map(fn ($node) => $node->id, $tree->roots()));
    }

    public function test_restricted_guides_are_absent_from_the_listing(): void
    {
        $this->asAuthor()->get('/admin/docs')
            ->assertOk()
            ->assertSee($this->guideHref('redactie'), false)
            ->assertDontSee($this->guideHref('admins'), false)
            ->assertDontSee($this->guideHref('beheer'), false)
            ->assertDontSee($this->guideHref('beheer/gebruikers'), false)
            ->assertDontSee($this->guideHref('onbekende-rol'), false);
    }

    public function test_an_admin_with_the_role_finds_restricted_guides_in_the_listing(): void
    {
        $this->asAdmin()->get('/admin/docs')
            ->assertOk()
            ->assertSee($this->guideHref('admins'), false)
            ->assertSee($this->guideHref('beheer/gebruikers'), false);
    }

    private function guideHref(string $guideId): string
    {
        return 'href="'.route('chief.docs.show', $guideId).'"';
    }
}
