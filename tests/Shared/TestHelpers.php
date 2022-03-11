<?php


namespace Thinktomorrow\Chief\Tests\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert;
use Thinktomorrow\Chief\Admin\Authorization\AuthorizationDefaults;
use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Site\Urls\ChiefResponse;
use Thinktomorrow\Chief\Site\Urls\MemoizedUrlRecords;

trait TestHelpers
{
    public function registerResponseMacros()
    {
        TestResponse::macro('assertViewCount', function ($key, $count) {
            Assert::assertArrayHasKey($key, $this->getOriginalContent()->getData());
            Assert::assertCount($count, $this->getOriginalContent()->getData()[$key]);
        });

        TestResponse::macro('assertContains', function ($value) {
            Assert::assertRegExp("/$value/mi", $this->getContent());
        });

        TestResponse::macro('assertNotContains', function ($value) {
            Assert::assertNotRegExp("/$value/mi", $this->getContent());
        });
    }

    protected function assertValidation(Model $model, $field, array $params, $coming_from_url, $submission_url, $assert_count = 0, $method = 'post'): TestResponse
    {
        $response = $this->actingAs($this->developer(), 'chief')
                         ->from($coming_from_url)
                         ->{$method}($submission_url, $params);

        $response->assertStatus(302);
        $response->assertRedirect($coming_from_url);
        $response->assertSessionHasErrors($field);

        $this->assertEquals($assert_count, $model->count());

        return $response;
    }

    protected function asAdminWithoutRole()
    {
        return $this->actingAs($this->fakeUser(), 'chief');
    }

    protected function asDeveloper()
    {
        return $this->actingAs($this->developer(), 'chief');
    }

    protected function asAdmin()
    {
        // Allow multiple calls in one test run.
        if (($admin = User::first()) && $this->isAuthenticated('chief')) {
            return $this->actingAs($admin, 'chief');
        }

        $admin = $this->fakeUser();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin']));

        return $this->actingAs($admin, 'chief');
    }

    protected function asAuthor()
    {
        $author = $this->fakeUser();
        $author->assignRole(Role::firstOrCreate(['name' => 'author']));

        return $this->actingAs($author, 'chief');
    }

    protected function developer()
    {
        $developer = $this->fakeUser();
        $developer->assignRole(Role::firstOrCreate(['name' => 'developer']));

        return $developer;
    }

    protected function fakeUser(array $values = []): User
    {
        return User::create(array_merge([
            'firstname' => 'Ben',
            'lastname' => Str::random(),
            'email' => Str::random() . '@example.com',
        ], $values));
    }

    protected function admin()
    {
        $admin = $this->fakeUser();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin']));

        return $admin;
    }

    protected function author()
    {
        $author = $this->fakeUser();
        $author->assignRole(Role::firstOrCreate(['name' => 'author']));

        return $author;
    }



    protected function setUpChiefEnvironment()
    {
        $this->setUpDefaultAuthorization();

        $this->disableSiteRouteCatchAll();
    }

    /**
     * Because our site route catch all is registered on boot, and our tests usually contain registrations of routes after the application boot,
     * we'll need to make sure the catch all is not in effect so that our testroute endpoints will be hit.
     */
    protected function disableSiteRouteCatchAll()
    {
        if (isset($this->keepOriginalSiteRoute) && $this->keepOriginalSiteRoute) {
            return;
        }

        Route::get('{slug?}', function ($slug = '/') {
            return ChiefResponse::fromSlug($slug);
        })->name('pages.show');
    }

    protected function updateLinks(Model $model, array $links): TestResponse
    {
        $response = $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => get_class($model),
            'modelId' => $model->id,
            'links' => $links,
        ]);

        MemoizedUrlRecords::clearCachedRecords();

        return $response;
    }

    protected function setUpDefaultAuthorization()
    {
        AuthorizationDefaults::permissions()->each(function ($permissionName) {
            Artisan::call('chief:permission', ['name' => $permissionName]);
        });

        AuthorizationDefaults::roles()->each(function ($defaultPermissions, $roleName) {
            Artisan::call('chief:role', ['name' => $roleName, '--permissions' => implode(',', $defaultPermissions)]);
        });
    }

    protected function withoutDefaultAuthorization()
    {
        Permission::all()->each->delete();
        Role::all()->each->delete();
    }

    protected function verifyMailRender(MailMessage $mailMessage): void
    {
        $flag = false;

        view($mailMessage->view, $mailMessage->viewData)->render(function () use (&$flag) {
            $flag = true;
        });

        $this->assertTrue($flag, 'Mail [' . $mailMessage->view . '] view could\'nt be rendered!');
    }

//    protected function invokePrivateMethod($object, $methodName, array $parameters = [])
//    {
//        $reflection = new \ReflectionClass(get_class($object));
//        $method = $reflection->getMethod($methodName);
//        $method->setAccessible(true);
//
//        return $method->invokeArgs($object, $parameters);
//    }



    private function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
