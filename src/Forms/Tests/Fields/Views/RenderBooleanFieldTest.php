<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Views;

use Thinktomorrow\Chief\Forms\Fields\Boolean;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RenderBooleanFieldTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_render_boolean_field()
    {
        $component = Boolean::make('xxx')->optionLabel('positieve keuze')->value(true);
        $this->assertStringContainsString('name="xxx', $component->toHtml());
        $this->assertStringContainsString(1, $component->toHtml());
    }

    public function test_it_can_render_localized_fields()
    {
        $component = Boolean::make('xxx')->optionLabel('positieve keuze')->locales(['nl', 'en'])->value([
            'nl' => $valueNL = true,
            'en' => $valueEN = false,
        ]);

        $render = $component->toHtml();

        $this->assertStringContainsString('xxx[nl]', $render);
        $this->assertStringContainsString('xxx[en]', $render);
        $this->assertStringContainsString(1, $render);
        $this->assertStringContainsString(0, $render);
    }

    public function test_it_can_render_as_preview()
    {
        $component = Boolean::make('xxx')->optionLabel('positieve keuze')->value(true);
        $this->assertStringContainsString(1, $component->renderPreview());
    }

    public function test_it_can_toggle_fields_when_a_boolean_is_off()
    {
        $component = Boolean::make('manually_composed')
            ->toggleField('odoo_id', false)
            ->toggleField('preserve_non_odoo_variants', false)
            ->toggleField('other_field', true);

        $this->assertSame([
            'odoo_id' => ['0'],
            'preserve_non_odoo_variants' => ['0'],
            'other_field' => ['1'],
        ], $component->getFieldToggles());
    }
}
