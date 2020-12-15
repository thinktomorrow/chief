<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class SortModelsTest extends TestCase
{
    private $fake;

    protected function setUp(): void
    {
        parent::setUp();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);

        $this->fake = (new ManagerFake(app(Register::class)->filterByKey('managed_model_first')->first()));
    }

    /** @test */
    public function it_can_sort_a_model()
    {
        $model1 =ManagedModelFakeFirst::create(['title' => 'Foobar', 'order' => 1]);
        $model2 = ManagedModelFakeFirst::create(['title' => 'Foobar 2', 'order' => 2]);
        $model3 = ManagedModelFakeFirst::create(['title' => 'Foobar 3', 'order' => 3]);

        $this->asAdmin()
            ->post(route('chief.api.sort'), [
                'modelType' => ManagedModelFakeFirst::class,
                'indices' => [
                    1 => $model1->id,
                    4 => $model2->id,
                    3 => $model3->id,
                ],
            ]);

        $this->assertEquals(1, $model1->fresh()->order);
        $this->assertEquals(4, $model2->fresh()->order);
        $this->assertEquals(3, $model3->fresh()->order);
    }

    /** @test */
    public function it_can_fetch_models_in_order_of_manual_sort()
    {
        $model1 = ManagedModelFakeFirst::create(['title' => 'Foobar', 'order' => 3]);
        $model2 = ManagedModelFakeFirst::create(['title' => 'Foobar 2', 'order' => 2]);
        $model3 = ManagedModelFakeFirst::create(['title' => 'Foobar 3', 'order' => 1]);

        $this->assertEquals([1,2,3], ManagedModelFakeFirst::sortedManually()->get()->pluck('order')->toArray());
    }
}
