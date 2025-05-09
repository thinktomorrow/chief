<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Users;

use Illuminate\Support\Facades\Hash;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ChangePasswordTest extends ChiefTestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User;
        $this->user->email = 'email';
        $this->user->firstname = 'firstname';
        $this->user->lastname = 'lastname';
        $this->user->password = Hash::make('password');
        $this->user->save();
    }

    public function test_only_logged_in_user_can_update_password()
    {
        $this->assertFalse(auth()->guard('chief')->check());

        $response = $this->put(route('chief.back.password.update'), ['password' => 'new password', 'password_confirm' => 'new password']);
        $response->assertRedirect(route('chief.back.login'));

        // Assert password remains the same
        $this->assertTrue(Hash::check('password', $this->user->fresh()->password));
    }

    public function test_when_user_fills_in_password_prompt_password_gets_updated()
    {
        $response = $this->actingAs($this->user, 'chief')
            ->put(route('chief.back.password.update'), ['password' => 'new password', 'password_confirmation' => 'new password']);

        $response->assertRedirect(route('chief.back.dashboard'));

        // Assert password is changed
        $this->assertTrue(Hash::check('new password', $this->user->fresh()->password));
    }
}
