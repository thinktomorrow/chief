<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\FragmentModel;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class UpdateFragmentsTest extends TestCase
{
    private $fake;
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);

        $this->model = ManagedModelFakeFirst::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->fake = (new ManagerFake(app(Register::class)->filterByKey('managed_model_first')->first()))->manage($this->model);
    }

    /** @test */
    public function it_can_update_a_fragment()
    {
        $this->asAdmin()
            ->put($this->fake->route('update'), [
                'fragment-field' => [
                    ['title' => 'fragment-title-1', 'content' => ['nl' => 'fragment-content-1-nl', 'en' => 'fragment-content-1-en']],
                    ['title' => 'fragment-title-2', 'content' => ['nl' => 'fragment-content-2-nl', 'en' => 'fragment-content-2-en']],
                ],
            ]);

        $first = ManagedModelFakeFirst::first();

        // There are two fragment records created
        $fragmentModels = FragmentModel::all();

        $this->assertEquals([
            Fragment::fromModel($fragmentModels[0]),
            Fragment::fromModel($fragmentModels[1]),
        ], $first->getFragments('fragment-field'));
    }
}
