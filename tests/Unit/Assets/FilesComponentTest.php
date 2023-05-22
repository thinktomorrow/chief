<?php

namespace Thinktomorrow\Chief\Tests\Unit\Assets;

use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\FilesComponent;
use Thinktomorrow\Chief\Tests\Unit\Forms\TestCase;

class FilesComponentTest extends TestCase
{
    // https://christoph-rumpel.com/2021/4/how-I-test-livewire-components
    // https://laravel-livewire.com/docs/2.x/testing#testing-component-presence
    public function test_it_can_create_component()
    {
        // :field-id="$field->getId()"
        //        :field-name="$field->getName($locale)"
        //        :allow-multiple="$field->allowMultiple()"
        //        :existing-files="$field->getValue($locale)"
        //        :components="$field->getComponents()"

        Livewire::test(FilesComponent::class, ['fieldName' => 'xxx'])
            ->assertSet('fieldName','xxx')
            ->assertSeeHtml('id="xxx"');
    }

    public function test_it_can_create_component_with_existing_assets()
    {
        Livewire::test(FilesComponent::class, ['fieldName' => 'xxx', 'existingFiles' => ])
            ->assertSet('fieldName','xxx')
            ->assertSeeHtml('id="xxx"');
    }

    public function test_it_has_input_values_when_at_least_one_asset_is_present()
    {
        Livewire::test(FilesComponent::class, ['fieldName' => 'xxx'])
            ->assertSeeHtml('name="xxx"');
    }
}
