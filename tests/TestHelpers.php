<?php


namespace Chief\Tests;


use Chief\Authorization\AuthorizationDefaults;
use Chief\Authorization\Role;
use Chief\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

trait TestHelpers
{
    protected function assertValidation(Model $model, $field, array $params, $coming_from_url, $submission_url, $assert_count = 0, $method = 'post')
    {
        $response = $this->actingAs($this->developer(), 'admin')
                         ->from($coming_from_url)
                         ->{$method}($submission_url, $params);

        $response->assertStatus(302);
        $response->assertRedirect($coming_from_url);
        $response->assertSessionHasErrors($field);

        $this->assertEquals($assert_count, $model->count());
    }

    protected function asDefaultAdmin()
    {
        return $this->actingAs(factory(User::class)->make(), 'admin');
    }

    protected function asDeveloper()
    {
        return $this->actingAs($this->developer(), 'admin');
    }

    protected function asAdmin()
    {
        $admin = factory(User::class)->create();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']));

        return $this->actingAs($admin, 'admin');
    }

    protected function asAuthor()
    {
        $author = factory(User::class)->create();
        $author->assignRole(Role::firstOrCreate(['name' => 'author', 'guard_name' => 'admin']));

        return $this->actingAs($author, 'admin');
    }

    protected function developer()
    {
        $developer = factory(User::class)->create();
        $developer->assignRole(Role::firstOrCreate(['name' => 'developer', 'guard_name' => 'admin']));

        return $developer;
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