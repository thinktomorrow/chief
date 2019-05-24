<?php

namespace Thinktomorrow\Chief\Tests\Feature\Users;

use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class DisableUserTest extends TestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->user = $this->author();

        // Fake password so we can login with a known value
        $this->user->password = Hash::make('password');
        $this->user->save();

        // We start with an enabled user
        $this->user->enable();
    }

    /** @test */
    public function user_can_be_disabled_by_admin()
    {
        $this->assertTrue($this->user->isEnabled());

        $this->asAdmin()->post(route('chief.back.users.disable', $this->user->id));

        $this->assertFalse($this->user->fresh()->isEnabled());
    }

    /** @test */
    public function admin_can_not_disable_themself()
    {
        $admin = $this->admin();
        $admin->enable();

        $this->assertTrue($admin->isEnabled());

        $response = $this->actingAs($admin, 'chief')->post(route('chief.back.users.disable', $admin->id));

        $response->assertSessionHas('messages.error', 'U kan uzelf niet blokkeren.');

        $this->assertTrue($this->user->fresh()->isEnabled());
    }

    /** @test */
    public function user_cannot_be_disabled_by_regular_author()
    {
        $this->assertTrue($this->user->isEnabled());

        $this->asAuthor()->post(route('chief.back.users.disable', $this->user->id));

        $this->assertTrue($this->user->fresh()->isEnabled());
    }

    /** @test */
    public function disabled_user_cannot_log_in()
    {
        $this->user->disable();

        $response = $this->post(route('chief.back.login.store'), [
            'email'    => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
    }

    /** @test */
    public function disabled_user_cannot_use_invite_link()
    {
        $this->markTestIncomplete();
    }
}
