<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminLoginTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function non_authenticated_are_kept_out()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function entering_valid_login_credentials_lets_you_pass()
    {
        $admin = factory(User::class)->create([
            'email' => 'foo@example.com'
        ]);

        $response = $this->post(route('admin.login.store'),[
            'email' => 'foo@example.com',
            'password' => 'foobar',
        ]);

        $this->assertEquals($admin->id, Auth::user()->id);
        $this->assertFalse(session()->has('errors'));
        $response->assertRedirect(route('admin.home'));
    }

    /** @test */
    public function entering_invalid_login_credentials_keeps_you_out()
    {
        factory(User::class)->create([
            'email' => 'foo@example.com'
        ]);

        // Enter invalid credentials
        $response = $this->post(route('admin.login.store'),[
            'email' => 'foo@example.com',
            'password' => 'xxx',
        ]);

        $this->assertNull(Auth::user());
        $this->assertTrue(session()->has('errors'));
        $response->assertRedirect(route('home'));
    }

    /** @test */
    public function it_displays_admin_page_for_authenticated()
    {
        $admin = factory(User::class)->create();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $this->assertInstanceOf(User::class, Auth::user());
        $this->assertFalse(session()->has('errors'));
    }

    /** @test */
    public function it_redirects_authenticated_admin_to_intended_page()
    {
        $admin = factory(User::class)->create([
            'email' => 'foo@example.com'
        ]);

        $resp = $this->get(route('admin.articles.index'));
        $resp->assertRedirect(route('admin.login'));

        $response = $this->post(route('admin.login.store'),[
            'email' => 'foo@example.com',
            'password' => 'foobar',
        ]);

        $this->assertEquals($admin->id, Auth::user()->id);
        $this->assertFalse(session()->has('errors'));
        $response->assertRedirect(route('admin.articles.index'));
    }

    /** @test */
    public function it_can_log_you_out()
    {
        $admin = factory(User::class)->create();

        Auth::login($admin);

        $this->assertEquals($admin->id, Auth::user()->id);

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('home'));

        $this->assertNull(Auth::user());
    }

    /** @test */
    public function it_can_send_a_password_reset_mail()
    {
        Notification::fake();

        $admin = factory(User::class)->create([
            'email'     => 'foo@example.com',
            'password'  => 'IForgotThisPassword'
        ]);

        $response = $this->post(route('password.email'),[
            'email' => 'foo@example.com'
        ]);

        Notification::assertSentTo(
            $admin,
            ResetPassword::class
        );
    }

    /** @test */
    public function it_can_reset_your_password()
    {
        $admin = factory(User::class)->create([
            'email'     => 'foo@example.com',
            'password'  => 'IForgotThisPassword'
        ]);

        DB::insert('INSERT INTO password_resets (email, token, created_at) VALUES(?, ?, ?)', ["foo@example.com", bcrypt("71594f253f7543eca5d884b37c637b0611b6a40809250c2e5ba2fbc9db74916c"), Carbon::now()]);

        $response = $this->post(url('password/reset'), [
            'token'                 => "71594f253f7543eca5d884b37c637b0611b6a40809250c2e5ba2fbc9db74916c",
            'email'                 => "foo@example.com",
            'password'              => "password",
            'password_confirmation' => "password",
        ]);

        $response->assertRedirect(route('admin.home'));

        Auth::logout();

        $response = $this->post(route('admin.login.store'),[
            'email'     => 'foo@example.com',
            'password'  => 'password',
        ]);

        $this->assertFalse(session()->has('errors'));
        $response->assertRedirect(route('admin.home'));

    }

    /** @test */
    public function it_will_redirect_if_logged_in_when_trying_to_log_in()
    {
        $admin = factory(User::class)->create([
            'email'     => 'foo@example.com'
        ]);

        Auth::login($admin);

        $this->assertEquals($admin->id, Auth::user()->id);

        $response = $this->post(route('admin.login.store'),[
            'email' => 'foo@example.com',
            'password' => 'foobar',
        ]);

        $response->assertRedirect(route('admin.home'));
    }

    /** @test */
    public function it_returns_a_json_error_if_unauthenticated_request_expects_json_response()
    {
        $response = $this->get('/admin', [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(401);
    }
}
