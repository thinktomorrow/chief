<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Authentication;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class AdminDisabledLoginTest extends ChiefTestCase
{
    public function test_entering_valid_login_credentials_for_disabled_user_wont_let_you_pass()
    {
        $admin = $this->fakeUser([
            'email' => 'foo@example.com',
            'password' => bcrypt('foobar'),
            'enabled' => false,
        ]);

        $response = $this->post(route('chief.back.login.store'), [
            'email' => 'foo@example.com',
            'password' => 'foobar',
        ]);

        $this->assertFalse(Auth::guard('chief')->check());
        $response->assertRedirect('/');
    }

    public function test_disabled_user_is_logged_out_during_active_session()
    {
        $admin = $this->fakeUser([
            'enabled' => true,
        ]);

        $this->actingAs($admin, 'chief')->get('/admin');

        $this->assertTrue(Auth::guard('chief')->check());

        // Admin wordt gedeactiveerd
        $admin->disable();

        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
        $this->assertFalse(Auth::guard('chief')->check());
    }

    public function test_disabled_user_cannot_be_reauthenticated_via_remember_me_cookie()
    {
        $admin = $this->fakeUser([
            'email' => 'foo@example.com',
            'password' => bcrypt('foobar'),
            'enabled' => true,
        ]);

        $response = $this->post(route('chief.back.login.store'), [
            'email' => 'foo@example.com',
            'password' => 'foobar',
            'remember' => true,
        ]);

        $this->assertTrue(Auth::guard('chief')->check());

        $admin->disable();

        $this->app['session']->flush();
        $this->app['auth']->forgetGuards();

        $recallerName = Auth::guard('chief')->getRecallerName();

        $rememberCookie = collect($response->headers->getCookies())
            ->first(fn ($cookie) => $cookie->getName() === $recallerName);

        $this->assertNotNull($rememberCookie);

        $response = $this
            ->withCookie($recallerName, $rememberCookie->getValue())
            ->get('/admin');

        $response->assertRedirect('/admin/login');
        $this->assertFalse(Auth::guard('chief')->check());
    }

    public function test_disabled_user_cannot_request_password_reset()
    {
        Notification::fake();

        $admin = $this->fakeUser([
            'email' => 'foo@example.com',
            'enabled' => false,
        ]);

        $this->post(route('chief.back.password.email'), [
            'email' => 'foo@example.com',
        ]);

        Notification::assertNothingSent();
    }

    public function test_session_id_is_regenerated_on_login()
    {
        $admin = $this->fakeUser([
            'password' => bcrypt('foobar'),
        ]);

        $initialSessionId = session()->getId();

        $this->post(route('chief.back.login.store'), [
            'email' => $admin->email,
            'password' => 'foobar',
        ]);

        $this->assertNotEquals($initialSessionId, session()->getId());
    }

    public function test_reenabled_user_is_not_automatically_logged_back_in(): void
    {
        $admin = $this->fakeUser([
            'password' => bcrypt('foobar'),
            'enabled' => true,
        ]);

        $loginResponse = $this->post(route('chief.back.login.store'), [
            'email' => $admin->email,
            'password' => 'foobar',
            'remember' => true,
        ]);

        $this->assertAuthenticated('chief');

        $recallerName = Auth::guard('chief')->getRecallerName();
        $rememberCookie = collect($loginResponse->headers->getCookies())
            ->first(fn ($cookie) => $cookie->getName() === $recallerName);

        $this->assertNotNull($rememberCookie);

        // Disable → logout via middleware (met remember-cookie!)
        $admin->update(['enabled' => false]);

        $this->app['auth']->forgetGuards();

        $this
            ->withCookie($recallerName, $rememberCookie->getValue())
            ->get('/admin');

        $this->assertGuest('chief');

        // Re-enable user
        $admin->update(['enabled' => truech]);

        // Simuleer nieuwe request met oude cookies
        $this->app['session']->flush();
        $this->app['auth']->forgetGuards();

        $response = $this
            ->withSession([])
            ->withCookie($recallerName, $rememberCookie->getValue())
            ->get('/admin');

        $response->assertRedirect('/admin/login');
        $this->assertGuest('chief');
    }
}
