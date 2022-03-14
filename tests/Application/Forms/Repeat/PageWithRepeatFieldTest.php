<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Forms\Repeat;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

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

        $response = $this->asAdmin()->get($this->manager($pageStub)->route('repeat-section', $pageStub, 'repeat_values', 2));
        $response->assertStatus(200);

        $responseData = $response->getOriginalContent()['data'];
        $this->assertStringContainsString('name="repeat_values[2][first]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[2][second]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[2][grid-first]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[2][grid-second]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[2][nested][0][nested-first]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[2][nested][0][nested-second]"', $responseData);
    }

    /** @test */
    public function it_can_save_repeat_sections_as_json()
    {

    }

    /** @test */
    public function it_can_save_nested_repeat_sections()
    {

    }

    /** @test */
    public function it_can_populate_nested_repeat_sections()
    {

    }
}
