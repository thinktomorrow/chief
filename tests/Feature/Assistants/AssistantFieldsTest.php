<?php

namespace Thinktomorrow\Chief\Tests\Feature\Assistants;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class AssistantFieldsTest extends TestCase
{
    /** @var Manager */
    private $manager;

    public function setUp(): void
    {
        parent::setUp();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        app(Register::class)->register(ProductManagerWithAssistantFake::class, ManagedModelFakeFirst::class);

        $this->manager = app(Managers::class)->findByKey('managed_model_first');

        $this->setUpDefaultAuthorization();
    }

    /** @test */
    public function an_assistant_can_provide_own_set_of_fields()
    {
        $this->assertCount(0, $this->manager->fields()->filterBy('key', 'assistant-input'));
        $this->assertCount(1, $this->manager->fieldsWithAssistantFields()->filterBy('key', 'assistant-input'));
    }

    /** @test */
    public function assistant_fields_can_be_handled_by_assistant()
    {
        AssistantSaveMethodVerification::unset();

        $this->asAdmin()
            ->put($this->manager->manage(ManagedModelFakeFirst::create())->route('update'), [
                'assistant-input' => 'foobar',
            ]);

        $this->assertEquals('foobar', AssistantSaveMethodVerification::$value);
    }
}
