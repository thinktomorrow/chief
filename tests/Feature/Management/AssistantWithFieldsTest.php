<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Management\Assistants\Assistant;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
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

class ProductManagerWithAssistantFake extends ManagerFake
{
    protected $assistants = [
        'fake-assistant' => FakeAssistantWithFields::class,
    ];
}

class FakeAssistantWithFields implements Assistant
{
    private $manager;

    public function manager(Manager $manager)
    {
        $this->manager = $manager;
    }

    public static function key(): string
    {
        return 'fake-assistant';
    }

    public function route($verb): ?string
    {
        return null;
    }

    public function fields(): Fields
    {
        return new Fields([
            InputField::make('assistant-input'),
        ]);
    }

    public function saveAssistantInputField(Field $field, Request $request)
    {
        AssistantSaveMethodVerification::set($request->get('assistant-input'));
    }

    public function can($verb): bool
    {
        return true;
    }

    public function guard($verb): Assistant
    {
        return $this;
    }
}

/**
 * convenience class to check if a method from the assistant (above) has been reached / called
 */
class AssistantSaveMethodVerification{

    public static $value = null;

    public static function set($value)
    {
        static::$value = $value;
    }

    public static function unset()
    {
        static::$value = null;
    }
}
