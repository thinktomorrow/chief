<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\FragmentAssistant;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;
use Thinktomorrow\Chief\Tests\Shared\ManagedModelFactory;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;

class DeleteFragmentActionTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = ManagedModelFactory::make()->create();

        $this->manager = ManagerFactory::make()
            ->withAssistants([CrudAssistant::class])
            ->withModel($this->model)
            ->create();
    }

    /** @test */
    public function it_can_delete_a_model()
    {
        $response = $this->asAdmin()->delete($this->manager->route('delete', $this->model), [
            'deleteconfirmation' => 'DELETE',
        ]);

        $response->assertStatus(302)->assertRedirect($this->manager->route('index'));

        $this->assertEquals(0, $this->model::count());
    }

    /** @test */
    public function it_cannot_delete_a_model_without_confirmation()
    {
        $response = $this->asAdmin()->delete($this->manager->route('delete', $this->model), [
            'deleteconfirmation' => 'xxx',
        ]);

        $response->assertStatus(302);

        $this->assertEquals(1, $this->model::count());
    }
}
