<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminLoginTest extends TestCase
{
    /** @test */
    public function it_can_display_admin_login()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
//        $this->assertStatus(200);
    }
}
