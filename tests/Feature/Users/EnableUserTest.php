<?php

namespace Thinktomorrow\Chief\Tests\Feature\Users;

use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class EnableUserTest extends TestCase
{
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->user = $this->author();

        // Fake password so we can login with a known value
        $this->user->password = Hash::make('password');
        $this->user->save();

        // We start with an disabled user
        $this->user->disable();
    }

    /** @test */
    function user_can_be_enabled_by_admin()
    {
        $this->assertFalse($this->user->isEnabled());

        $this->asAdmin()->post(route('chief.back.users.enable', $this->user->id));

        $this->assertTrue($this->user->fresh()->isEnabled());
    }

    /** @test */
    function user_cannot_be_enabled_by_regular_author()
    {
        $this->assertFalse($this->user->isEnabled());

        $this->asAuthor()->post(route('chief.back.users.disable', $this->user->id));

        $this->assertFalse($this->user->fresh()->isEnabled());
    }

    /** @test */
    function pending_invited_user_cannot_be_enabled()
    {
        $this->markTestIncomplete();
    }

}