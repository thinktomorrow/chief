<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Forms\Repeat;

use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class FragmentWithRepeatFieldTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(FragmentStub::class);
    }

    /** @test */
    public function it_can_retrieve_a_new_repeat_section()
    {
        $stub = new FragmentStub();

        $response = $this->asAdmin()->get($this->manager($stub)->route('repeat-section', 'repeat_values'). '?index=99');
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
    public function it_can_retrieve_a_new_repeat_section_for_existing_fragment()
    {
        $owner = $this->setupAndCreateArticle();
        $stub = $this->createAsFragment(new FragmentStub(), $owner);

        $response = $this->asAdmin()->get($this->manager($stub)->route('repeat-section', 'repeat_values', $stub->fragmentModel()->id). '?index=99');
        $response->assertStatus(200);

        $responseData = $response->getOriginalContent()['data'];
        $this->assertStringContainsString('name="repeat_values[99][first]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[99][second]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[99][grid-first]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[99][grid-second]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[99][nested][0][nested-first]"', $responseData);
        $this->assertStringContainsString('name="repeat_values[99][nested][0][nested-second]"', $responseData);
    }
}
