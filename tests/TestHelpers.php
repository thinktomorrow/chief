<?php


namespace Chief\Tests;


use Chief\Users\User;
use Illuminate\Database\Eloquent\Model;

trait TestHelpers
{
    protected function assertValidation(Model $model, $field, array $params, $coming_from_url, $submission_url, $assert_count = 0)
    {
        $response = $this->actingAs(factory(User::class)
                         ->create())
                         ->from($coming_from_url)
                         ->post($submission_url, $params);

        $response->assertStatus(302);
        $response->assertRedirect($coming_from_url);
        $response->assertSessionHasErrors($field);

        $this->assertEquals($assert_count, $model->count());
    }
}