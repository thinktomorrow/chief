<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFakeWithValidation;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldValidatorTest extends TestCase
{
    private $fake;
    private $model;

    protected function setUp()
    {
        parent::setUp();

        ManagedModelFake::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('fakes', ManagerFakeWithValidation::class, ManagedModelFake::class);

        $this->model = ManagedModelFake::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->fake = (new ManagerFakeWithValidation(app(Register::class)->filterByKey('fakes')->first()))->manage($this->model);
    }

    /** @test */
    public function a_required_field_can_be_validated()
    {
        $this->assertValidation(new ManagedModelFake(), 'title', $this->payload(['title' => '']),
            $this->fake->route('edit'),
            $this->fake->route('update'),
            1, 'put'
        );
    }

    /** @test */
    public function a_required_translatable_field_can_be_validated()
    {
        config()->set('app.fallback_locale', 'nl');

        $this->assertValidation(new ManagedModelFake(), 'trans.nl.title_trans', $this->payload(['trans.nl.title_trans' => '']),
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

        $response->assertSessionHasNoErrors();
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
            array_set($params, $key, $value);
        }

        return $params;
    }
}
