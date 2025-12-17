<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Authentication;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class AdminRememberMeTest extends ChiefTestCase
{
    public function test_password_change_invalidates_remember_me_cookie()
    {
        $admin = $this->fakeUser([
            'password' => bcrypt('foobar'),
            'enabled' => true,
        ]);

        $response = $this->post(route('chief.back.login.store'), [
            'email' => $admin->email,
            'password' => 'foobar',
            'remember' => true,
        ]);

        $this->assertTrue(Auth::guard('chief')->check());

        // Password reset
        $admin->forceFill([
            'password' => bcrypt('new-password'),
        ])->save();

        $this->app['session']->flush();
        $this->app['auth']->forgetGuards();

        $recallerName = Auth::guard('chief')->getRecallerName();
        $rememberCookie = collect($response->headers->getCookies())
            ->first(fn ($cookie) => $cookie->getName() === $recallerName);

        $response = $this
            ->withCookie($recallerName, $rememberCookie->getValue())
            ->get('/admin');

        $response->assertRedirect('/admin/login');
        $this->assertFalse(Auth::guard('chief')->check());
    }

    public function test_it_stays_logged_in_with_remember_me_cookie(): void
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

        // Fake new browser session
        $this->app['session']->flush();
        $this->app['auth']->forgetGuards();

        $recallerName = Auth::guard('chief')->getRecallerName();

        $rememberCookie = collect($loginResponse->headers->getCookies())
            ->first(fn ($cookie) => $cookie->getName() === $recallerName);

        $this->assertNotNull($rememberCookie);

        $response = $this
            ->withSession([])
            ->withCookie($recallerName, $rememberCookie->getValue())
            ->get('/admin');

        $response->assertStatus(200);
        $this->assertAuthenticated('chief');
        $this->assertEquals($admin->id, Auth::guard('chief')->id());
    }

    public function test_it_does_not_stay_logged_in_without_remember_me_cookie(): void
    {
        $admin = $this->fakeUser([
            'password' => bcrypt('foobar'),
            'enabled' => true,
        ]);

        $this->post(route('chief.back.login.store'), [
            'email' => $admin->email,
            'password' => 'foobar',
            'remember' => false,
        ]);

        $this->assertAuthenticated('chief');

        // Simuleer nieuwe browser sessie
        $this->app['session']->flush();
        $this->app['auth']->forgetGuards();

        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
        $this->assertGuest('chief');
    }

    public function test_it_invalidates_mismatch_of_remember_token(): void
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

        // Tamper with remember_token in DB
        DB::table('chief_users')
            ->where('id', $admin->id)
            ->update(['remember_token' => 'tampered-token']);

        // Nieuwe sessie
        $this->app['session']->flush();
        $this->app['auth']->forgetGuards();

        $response = $this
            ->withCookie($recallerName, $rememberCookie->getValue())
            ->get('/admin');

        $response->assertRedirect('/admin/login');
        $this->assertGuest('chief');
    }

    public function test_logout_clears_remember_me_cookie()
    {
        $admin = $this->fakeUser([
            'password' => bcrypt('foobar'),
        ]);

        $loginResponse = $this->post(route('chief.back.login.store'), [
            'email' => $admin->email,
            'password' => 'foobar',
            'remember' => true,
        ]);

        $recallerName = Auth::guard('chief')->getRecallerName();

        $rememberCookie = collect($loginResponse->headers->getCookies())
            ->first(fn ($cookie) => $cookie->getName() === $recallerName);

        $this->assertNotNull($rememberCookie);

        // 🔑 Stuur de remember-cookie mee bij logout (zoals in een echte browser)
        $logoutResponse = $this
            ->withCookie($recallerName, $rememberCookie->getValue())
            ->get(route('chief.back.logout'));

        $logoutResponse->assertRedirect('/');

        // ✅ Beste assert: check dat cookie expired wordt gezet
        $logoutResponse->assertCookieExpired($recallerName);

        // ✅ Check dat remember_token in DB gewist is (als je logout-service dat doet)
        $this->assertNull(
            DB::table('chief_users')->where('id', $admin->id)->value('remember_token')
        );
    }
}
