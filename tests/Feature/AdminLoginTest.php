<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminLoginTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function it_redirects_non_authenticated_to_admin_login()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function entering_valid_login_credentials_lets_you_pass()
    {
        $this->createAdminUser();

        $response = $this->post(route('admin.login.store'),[
            'email' => 'foo@example.com',
            'password' => 'foobar',
        ]);

        $response->assertRedirect(route('admin.home'));
    }

    /** @test */
    public function entering_invalid_login_credentials_keeps_you_out()
    {
        $this->createAdminUser();

        // Visit the login page
        $this->get(route('admin.login'));

        // Enter invalid credentials
        $response = $this->post(route('admin.login.store'),[
            'email' => 'foo@example.com',
            'password' => 'xxx',
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function it_can_display_admin_page_for_authenticated()
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
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
