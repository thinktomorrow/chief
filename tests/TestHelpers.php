<?php


namespace Thinktomorrow\Chief\Tests;


use Thinktomorrow\Chief\Authorization\AuthorizationDefaults;
use Thinktomorrow\Chief\Authorization\Role;
use Thinktomorrow\Chief\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Assert;

trait TestHelpers
{
    public function registerResponseMacros()
    {
        TestResponse::macro('assertViewIsPassed', function ($value) {
            Assert::assertArrayHasKey($value, $this->getOriginalContent()->getData());
        });

        TestResponse::macro('assertContains', function ($value) {
            Assert::assertRegExp("/$value/mi", $this->getContent());
        });

        TestResponse::macro('assertNotContains', function ($value) {
            Assert::assertNotRegExp("/$value/mi", $this->getContent());
        });
    }

    protected function assertValidation(Model $model, $field, array $params, $coming_from_url, $submission_url, $assert_count = 0, $method = 'post')
    {
        $response = $this->actingAs($this->developer(), 'chief')
                         ->from($coming_from_url)
                         ->{$method}($submission_url, $params);

        $response->assertStatus(302);
        $response->assertRedirect($coming_from_url);
        $response->assertSessionHasErrors($field);

        $this->assertEquals($assert_count, $model->count());
    }

    protected function asDefaultAdmin()
    {
        return $this->actingAs(factory(User::class)->make(), 'chief');
    }

    protected function asDeveloper()
    {
        return $this->actingAs($this->developer(), 'chief');
    }

    protected function asAdmin()
    {
        $admin = factory(User::class)->create();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']));

        return $this->actingAs($admin, 'chief');
    }

    protected function asAuthor()
    {
        $author = factory(User::class)->create();
        $author->assignRole(Role::firstOrCreate(['name' => 'author', 'guard_name' => 'admin']));

        return $this->actingAs($author, 'chief');
    }

    protected function developer()
    {
        $developer = factory(User::class)->create();
        $developer->assignRole(Role::firstOrCreate(['name' => 'developer', 'guard_name' => 'admin']));

        return $developer;
    }

    protected function admin()
    {
        $admin = factory(User::class)->create();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']));

        return $admin;
    }

    protected function author()
    {
        $author = factory(User::class)->create();
        $author->assignRole(Role::firstOrCreate(['name' => 'author', 'guard_name' => 'admin']));

        return $author;
    }

    protected function setUpDefaultAuthorization()
    {
        AuthorizationDefaults::permissions()->each(function($permissionName){
            Artisan::call('chief:permission', ['name' => $permissionName]);
        });

        AuthorizationDefaults::roles()->each(function($defaultPermissions, $roleName){
            Artisan::call('chief:role', ['name' => $roleName, '--permissions' => implode(',',$defaultPermissions)]);
        });
    }

    protected function verifyMailRender(MailMessage $mailMessage): void
    {
        $flag = false;

        view($mailMessage->view, $mailMessage->viewData)->render(function () use (&$flag)
        {
            $flag = true;
        });

        $this->assertTrue($flag, 'Mail [' . $mailMessage->view . '] view could\'nt be rendered!');
    }
}