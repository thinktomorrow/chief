<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Users;

use Illuminate\Support\Facades\Hash;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class DisableUserTest extends ChiefTestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->author();

        // Fake password so we can login with a known value
        $this->user->password = Hash::make('password');
        $this->user->save();

        // We start with an enabled user
        $this->user->enable();
    }

    public function test_user_can_be_disabled_by_admin()
    {
        $this->assertTrue($this->user->isEnabled());

        $this->asAdmin()->post(route('chief.back.users.disable', $this->user->id));

        $this->assertFalse($this->user->fresh()->isEnabled());
    }

    public function test_admin_can_not_disable_themself()
    {
        $admin = $this->admin();
        $admin->enable();

        $this->assertTrue($admin->isEnabled());

        $response = $this->actingAs($admin, 'chief')->post(route('chief.back.users.disable', $admin->id));

        $response->assertSessionHas('messages.error', 'U kan uzelf niet blokkeren.');

        $this->assertTrue($this->user->fresh()->isEnabled());
    }

    public function test_user_cannot_be_disabled_by_regular_author()
    {
        $this->assertTrue($this->user->isEnabled());

        $this->asAuthor()->post(route('chief.back.users.disable', $this->user->id));

        $this->assertTrue($this->user->fresh()->isEnabled());
    }

    public function test_disabled_user_cannot_log_in()
    {
        $this->user->disable();

        $response = $this->post(route('chief.back.login.store'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
    }
}
