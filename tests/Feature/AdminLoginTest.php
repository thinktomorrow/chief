<?php

namespace Thinktomorrow\Chief\Tests\Feature;

use Thinktomorrow\Chief\App\Notifications\ResetAdminPassword;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Users\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdminLoginTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    public function non_authenticated_are_kept_out()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function entering_valid_login_credentials_for_disabled_user_wont_let_you_pass()
    {
        $this->disableExceptionHandling();

        $admin = factory(User::class)->create([
            'email' => 'foo@example.com',
            'password' => bcrypt('foobar'),
            'enabled' => false,
        ]);

        $response = $this->post(route('chief.back.login.store'),[
            'email'     => 'foo@example.com',
            'password'  => 'foobar',
        ]);

        $this->assertFalse(Auth::guard('chief')->check());
        $response->assertRedirect('/');
    }

    /** @test */
    public function entering_valid_login_credentials_lets_you_pass()
    {
        $admin = factory(User::class)->create([
            'email' => 'foo@example.com',
            'password' => bcrypt('foobar'),
            'enabled' => true,
        ]);

        $response = $this->post(route('chief.back.login.store'),[
            'email'     => 'foo@example.com',
            'password'  => 'foobar',
        ]);

        $this->assertTrue(Auth::guard('chief')->check());
        $this->assertEquals($admin->id, Auth::guard('chief')->user()->id);
        $this->assertFalse(session()->has('errors'));
        $response->assertRedirect(route('chief.back.dashboard'));
    }

    /** @test */
    public function entering_invalid_login_credentials_keeps_you_out()
    {
        factory(User::class)->make([
            'email' => 'foo@example.com'
        ]);

        // Enter invalid credentials
        $response = $this->post(route('chief.back.login.store'),[
            'email' => 'foo@example.com',
            'password' => 'xxx',
        ]);

        $this->assertNull(Auth::guard('chief')->user());
        $this->assertTrue(session()->has('errors'));
        $response->assertRedirect('/');
    }

    /** @test */
    public function it_displays_admin_page_for_authenticated()
    {
        $this->disableExceptionHandling();

        $admin = factory(User::class)->make();
        $response = $this->actingAs($admin, 'chief')->get('/admin');

        $response->assertStatus(200);
        $this->assertInstanceOf(User::class, Auth::guard('chief')->user());
        $this->assertFalse(session()->has('errors'));
    }

    /** @test */
    public function it_redirects_authenticated_admin_to_intended_page()
    {
        $admin = factory(User::class)->create([
            'email' => 'foo@example.com',
            'password' => bcrypt('foobar'),
            'enabled' => true,
        ]);

        $resp = $this->get(route('chief.back.pages.index'));
        $resp->assertRedirect(route('chief.back.login'));

        $response = $this->post(route('chief.back.login.store'),[
            'email' => 'foo@example.com',
            'password' => 'foobar',
        ]);

        $this->assertTrue(Auth::guard('chief')->check());
        $this->assertEquals($admin->id, Auth::guard('chief')->user()->id);
        $this->assertFalse(session()->has('errors'));
        $response->assertRedirect(route('chief.back.pages.index'));
    }

    /** @test */
    public function it_can_log_you_out()
    {
        $this->disableExceptionHandling();

        $admin = factory(User::class)->make();

        Auth::guard('chief')->login($admin);

        $this->assertEquals($admin->id, Auth::guard('chief')->user()->id);

        $response = $this->get(route('chief.back.logout'));

        $response->assertRedirect('/');

        $this->assertNull(Auth::guard('chief')->user());
    }

    /** @test */
    public function it_can_send_a_password_reset_mail()
    {
        Notification::fake();

        $admin = factory(User::class)->create([
            'email'     => 'foo@example.com',
            'password'  => 'IForgotThisPassword',
            'enabled'   => true,
        ]);

        $response = $this->post(route('chief.back.password.email'),[
            'email' => 'foo@example.com'
        ]);

        Notification::assertSentTo(
            $admin,
            ResetAdminPassword::class
        );
    }

    /** @test */
    public function it_can_reset_your_password()
    {
        $admin = factory(User::class)->create([
            'email'     => 'foo@example.com',
            'password'  => 'IForgotThisPassword',
            'enabled'   => true,
        ]);

        DB::insert('INSERT INTO password_resets (email, token, created_at) VALUES(?, ?, ?)', ["foo@example.com", bcrypt("71594f253f7543eca5d884b37c637b0611b6a40809250c2e5ba2fbc9db74916c"), Carbon::now()]);

        $response = $this->post(route('chief.back.password.request'), [
            'token'                 => "71594f253f7543eca5d884b37c637b0611b6a40809250c2e5ba2fbc9db74916c",
            'email'                 => "foo@example.com",
            'password'              => "password",
            'password_confirmation' => "password",
        ]);

        $response->assertRedirect(route('chief.back.dashboard'));

        Auth::guard('chief')->logout();

        $response = $this->post(route('chief.back.login.store'),[
            'email'     => 'foo@example.com',
            'password'  => 'password',
        ]);

        $this->assertFalse(session()->has('errors'));
        $response->assertRedirect(route('chief.back.dashboard'));

    }

    /** @test */
    public function it_will_redirect_if_logged_in_when_trying_to_log_in()
    {
        $admin = factory(User::class)->make([
            'email'     => 'foo@example.com'
        ]);

        Auth::guard('chief')->login($admin);

        $this->assertEquals($admin->id, Auth::guard('chief')->user()->id);

        $response = $this->post(route('chief.back.login.store'),[
            'email' => 'foo@example.com',
            'password' => 'foobar',
        ]);

        $response->assertRedirect(route('chief.back.dashboard'));
    }

    /** @test */
    public function it_returns_a_json_error_if_unauthenticated_request_expects_json_response()
    {
        $response = $this->get('/admin', [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    function it_can_access_admin_via_helper()
    {
        $admin = factory(User::class)->make([
            'email'     => 'foo@example.com'
        ]);

        $this->assertNull(admin());

        Auth::guard('chief')->login($admin);
        $this->assertEquals(Auth::guard('chief')->user(), admin());
    }
}
