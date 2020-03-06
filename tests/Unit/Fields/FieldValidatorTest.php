<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFakeWithValidation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class FieldValidatorTest extends TestCase
{
    private $fake;
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ManagerFakeWithValidation::class, ManagedModelFakeFirst::class);

        $this->model = ManagedModelFakeFirst::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->fake = (new ManagerFakeWithValidation(app(Register::class)->filterByKey('managed_model_first')->first()))->manage($this->model);
    }

    /** @test */
    public function a_required_field_can_be_validated()
    {
        $this->assertValidation(new ManagedModelFakeFirst(), 'title', $this->payload(['title' => '']),
            $this->fake->route('edit'),
            $this->fake->route('update'),
            1, 'put'
        );
    }

    /** @test */
    public function a_required_translatable_field_can_be_validated()
    {
        config()->set('app.fallback_locale', 'nl');

        $this->assertValidation(new ManagedModelFakeFirst(), 'trans.nl.title_trans', $this->payload(['trans.nl.title_trans' => '']),
            $this->fake->route('edit'),
            $this->fake->route('update'),
            1, 'put'
        );
    }

    /** @test */
    public function a_non_default_translatable_field_is_not_validated_if_entire_translation_is_empty()
    {
        config()->set('app.fallback_locale', 'nl');

        $response = $this->actingAs($this->developer(), 'chief')
            ->put($this->fake->route('update'), $this->payload(['trans.en.title_trans' => '']));

        $this->assertNull(session('errors'));
    }

    protected function payload($overrides = [])
    {
        $params = [
            'title' => 'title updated',
            'custom' => 'custom updated',
            'trans' => [
                'nl' => [
                    'title_trans' => 'title_trans nl updated',
                ],
                'en' => [
                    'title_trans' => 'title_trans en updated',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }
}
