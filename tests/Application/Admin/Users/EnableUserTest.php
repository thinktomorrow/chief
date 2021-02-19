<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Users;

use Illuminate\Support\Facades\Hash;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class EnableUserTest extends ChiefTestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->author();

        // Fake password so we can login with a known value
        $this->user->password = Hash::make('password');
        $this->user->save();

        // We start with an disabled user
        $this->user->disable();
    }

    /** @test */
    public function user_can_be_enabled_by_admin()
    {
        $this->assertFalse($this->user->isEnabled());

        $this->asAdmin()->post(route('chief.back.users.enable', $this->user->id));

        $this->assertTrue($this->user->fresh()->isEnabled());
    }

    /** @test */
    public function user_cannot_be_enabled_by_regular_author()
    {
        $this->assertFalse($this->user->isEnabled());

        $this->asAuthor()->post(route('chief.back.users.disable', $this->user->id));

        $this->assertFalse($this->user->fresh()->isEnabled());
    }
}
