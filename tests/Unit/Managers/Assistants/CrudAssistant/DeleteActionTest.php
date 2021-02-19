<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\CrudAssistant;

use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\ManagedModelFactory;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class DeleteActionTest extends ChiefTestCase
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
