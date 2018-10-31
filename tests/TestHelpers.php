<?php


namespace Thinktomorrow\Chief\Tests;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Authorization\AuthorizationDefaults;
use Thinktomorrow\Chief\Authorization\Permission;
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

    protected function asAdminWithoutRole()
    {
        return $this->actingAs(factory(User::class)->make(), 'chief');
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

        $admin = factory(User::class)->create();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin']));

        return $this->actingAs($admin, 'chief');
    }

    protected function asAuthor()
    {
        $author = factory(User::class)->create();
        $author->assignRole(Role::firstOrCreate(['name' => 'author']));

        return $this->actingAs($author, 'chief');
    }

    protected function developer()
    {
        $developer = factory(User::class)->create();
        $developer->assignRole(Role::firstOrCreate(['name' => 'developer']));

        return $developer;
    }

    protected function admin()
    {
        $admin = factory(User::class)->create();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin']));

        return $admin;
    }

    protected function author()
    {
        $author = factory(User::class)->create();
        $author->assignRole(Role::firstOrCreate(['name' => 'author']));

        return $author;
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

    protected function dummySlimImagePayload($name = "tt-favicon.png")
    {
        return '{"server":null,"meta":{},"input":{"name":"'.$name.'","type":"image/png","size":5558,"width":32,"height":32,"field":null},"output":{"name":"'.$name.'","type":"image/png","width":32,"height":32,"image":"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAEp0lEQVRYR+1XTW8bVRQ94/HY46/4K4lTF1KipBASEbVpELQpICRAYlFgQRelCxZ8SLAoDRIFpC5Dmq5AINT+ASIEAqGABCskBIiEqiQNoVDR0CihiRvHju3Escfzhe6zY894nDhBrbrpkyxr5s1798w95553h9N1XcdtHNwdADvJQIUsHbIGyFqRPcFGPw4A/QCu+LetsS0KKHBB0zCVlHBxOYeZTAEJSYGkFgE4eA4hJ482nwO9YRf2h0WIvG1bQLYEQNsXVA3f/buGb+dXkZRUcHVejzTd4ODx5G4vjrT64KoDZFMAFHwmI+Hjy0lcz8p1A1vzraNRtOP1+0PoCjg3XV8TAAW/EF/Hh38kyzxvi9AaD/Ec8GpnCI+1uGuCsAAgvqdX8jhzKQ5F35DV/w1fXEeafKM7jIcj7pJMK/tZAKxIKt76NYZVknlpEK/3+BwIOHl2559MwTTfLPLY5RHY3GJWxlJetSAWeQ7DD0bQ4hZMIEwAKNC5P5P4IZYtlxTtpEPHya4wDkY87GpoMo6pFakc5NlWH461+9n1yNU0RudXa6asNyziVE+jiQoTgHhOwcmxRZb6ytsXAQx0h3GoxQOaem9iiZXkxnhujw/HOgLs8pO/UxidqwAwFg1RQVmgbG6MMgDifnQug5GZtAl9u0/AS/cGEXHZ4XXwDEAsKyOnVCjyO3mERDtbl8wrSEsq84jBko6MG1JpHm8PlD3CBODsVBwTibwJAPH7SMSDwy1uRD0CA/DjQhaxnFJ+rjvoRHdIZHNTiRyupApQNB3fzK+iWg0dDQ4MHmgu02AAoGNgLIZFw8ZGEb7T04TeJhcLMkQUGDRwtK0Bz7f52dwIUbCJBmg/v2DDuf4oeGbdQBmAput47ecFpAqV1G4AoLl3bxIAj53D+f7dzL4tACgDxtTeCgA+wYbz/VHYqzNAIhy6ZFb3rQDQ5hNwpi9SSwPAF7NpfH6NqsB8nlZTMDwZx2SyIlajBj69msJXhjKsNoSn7/Lixb1BaxUwF1uX8eZ4DKVj3uAFOk50hXF4lweUqa/nMvjsWhp2jkNO1XHkbh+OdxRL67flHD6YToAynFfJQcyDKmCv32n1AbpDTvj+dALj8ZzFyR4IOvF2T1OJOx05Rcf1NRmnJ5bQJPIY7ovAI5BV6yxwVtZw6sINZA1+QeV6el8TbAZ3qrJi4KcbWXx0OQGuigYC1+oV0BkQ2ZvG1xVMp/KQ1aJThp127G8UWXllJBW/r+SxJmtlrqlrGjwQwR6vw9SoWM6Cs1PLFjOypGMHN4gCDjpevi+EJ6JeS5dkArCcV3Dil0WUOq0dhNn60Rfa/Xim1bd1P0DiqlQBwHMc+hpduJKWkCqQoe6g0yzh8dpteKUziIearX2A5TAi7x4Yj7Ee8PGoF09FPQg6eawpGr6czeD7hSzyJKi6La8OwWbDoy1uHG3zsx5iK+hlCmLrCv5KSzjY7ILDZu5oSYDUoIzFc6zMZlcLSMtauVyp5MjhSGD7wiIONbsRcNAe9bNmOg2ZN2+xpvhdoDON5FUN64rOfMFt5+Cy21C0d65+kgyS2dZ3wU1TY42N7gC47Rn4Dw+ni78hQfokAAAAAElFTkSuQmCC"},"actions":{"rotation":null,"crop":{"x":0,"y":0,"height":32,"width":32,"type":"auto"},"size":null,"filters":{"sharpen":0},"minSize":{"width":0,"height":0}}}';
    }

    protected function dummyDocument($name = "tt-document.pdf", $sizeInKilobytes = 100)
    {
        return UploadedFile::fake()->create($name, $sizeInKilobytes);
    }


}
