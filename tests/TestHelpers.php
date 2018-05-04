<?php


namespace Chief\Tests;


use Chief\Authorization\AuthorizationDefaults;
use Chief\Authorization\Role;
use Chief\Users\User;
use Illuminate\Database\Eloquent\Model;
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

    protected function asAdmin()
    {
        return $this->actingAs(factory(User::class)->make(), 'admin');
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
}