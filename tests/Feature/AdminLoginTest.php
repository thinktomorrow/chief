<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
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
        $admin = $this->createAdminUser();

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
        $this->createAdminUser();

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
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $this->assertInstanceOf(User::class, Auth::user());
        $this->assertFalse(session()->has('errors'));
    }

    /** @test */
    public function it_redirects_authenticated_admin_to_intended_page()
    {
        $admin = $this->createAdminUser();

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

    private function createAdminUser()
    {
        return \App\User::create([
            'name'  => 'Master',
            'email' => 'foo@example.com',
            'password' => bcrypt('foobar')
        ]);
    }


}
