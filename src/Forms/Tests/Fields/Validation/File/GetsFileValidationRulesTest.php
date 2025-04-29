<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation\File;

use Illuminate\Validation\Factory;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class GetsFileValidationRulesTest extends FormsTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_field_has_no_validation_by_default()
    {
        $field = File::make('upload');

        $this->assertEquals([], $field->getRules());
    }

    public function test_field_can_be_made_required_with_custom_rule_mapping()
    {
        $field = File::make('upload')->required();

        $this->assertContains('file_required', $field->getRules());
    }

    public function test_field_can_have_custom_validation_rules_mapped()
    {
        $field = File::make('upload')->rules(['required', 'mimetypes:pdf']);

        $this->assertEquals(['file_required', 'file_mimetypes:pdf'], $field->getRules());
    }

    public function test_field_validator_applies_rules_per_locale()
    {
        $field = File::make('upload')
            ->locales(['nl', 'fr'])
            ->required()
            ->rules('mimetypes:pdf');

        $validator = $field->createValidatorInstance(app(Factory::class), []);

        $this->assertEquals([
            'files.upload.nl' => ['file_required', 'file_mimetypes:pdf'],
            'files.upload.fr' => ['file_required', 'file_mimetypes:pdf'],
        ], $validator->getRules());
    }
}
