<?php

namespace Thinktomorrow\Chief\Tests\Feature\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Management\Assistants\Assistant;

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
