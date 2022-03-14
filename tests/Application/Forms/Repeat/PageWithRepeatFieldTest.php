<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Forms\Repeat;

use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class PageWithRepeatFieldTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        PageStub::migrateUp();
        chiefRegister()->model(PageStub::class, PageManager::class);
    }

    /** @test */
    public function it_can_retrieve_a_new_repeat_section()
    {
        $pageStub = PageStub::create();

        $response = $this->asAdmin()->get($this->manager($pageStub)->route('repeat-section', $pageStub, 'repeat_values'). '?index=99');
        $response->assertStatus(200);

        $responseData = $response->getOriginalContent()['data'];
        $this->assertStringContainsString('name="repeat_values[99][first]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[99][second]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[99][grid-first]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[99][grid-second]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[99][nested][0][nested-first]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[99][nested][0][nested-second]"', $responseData);
    }

    /** @test */
    public function it_can_save_repeat_section()
    {
        $pageStub = PageStub::create();

        $response = $this->asAdmin()->put($this->manager($pageStub)->route('form-update', $pageStub, 'repeat_form'), [
            'repeat_values' => [
                [
                    'first' => 'first value',
                    'second' => 'second value',
                ],
            ],
        ]);

        $response->assertSuccessful();

        $values = $pageStub->fresh()->repeat_values;
        $this->assertEquals('first value', $values[0]['first']);
        $this->assertEquals('second value', $values[0]['second']);
    }

    /** @test */
    public function it_can_save_nested_repeat_section()
    {
        $pageStub = PageStub::create();

        $response = $this->asAdmin()->put($this->manager($pageStub)->route('form-update', $pageStub, 'repeat_form'), [
            'repeat_values' => [
                [
                    'nested' => [
                        [
                            'nested-first' => 'first value',
                            'nested-second' => 'second value',
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertSuccessful();

        $values = $pageStub->fresh()->repeat_values;
        $this->assertEquals('first value', $values[0]['nested'][0]['nested-first']);
        $this->assertEquals('second value', $values[0]['nested'][0]['nested-second']);
    }

    /** @test */
    public function it_can_populate_nested_repeat_sections()
    {
        $pageStub = PageStub::create();

        $this->asAdmin()->put($this->manager($pageStub)->route('form-update', $pageStub, 'repeat_form'), [
            'repeat_values' => [
                [
                    'nested' => [
                        [
                            'nested-first' => 'first value',
                            'nested-second' => 'second value',
                        ],
                    ],
                ],
            ],
        ]);

        $html = $pageStub->fresh()->field('repeat_values')->toHtml();

        $this->assertStringContainsString('value="first value"', $html);
        $this->assertStringContainsString('value="second value"', $html);
    }
}
