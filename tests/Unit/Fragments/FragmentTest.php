<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fragments;

use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Managers\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\FragmentableStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\OwnerStub;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class FragmentTest extends ChiefTestCase
{
    /** @var FragmentRepository */
    private $fragmentRepo;

    protected function setUp(): void
    {
        parent::setUp();

        OwnerStub::migrateUp();
        FragmentableStub::migrateUp();

        $this->fragmentRepo = app(FragmentRepository::class);
    }

    /** @test */
    public function a_fragmentable_can_be_added_as_a_fragment()
    {
        $owner = OwnerStub::create();
        ManagerFactory::make()
            ->withAssistants([FragmentAssistant::class])
            ->withModel($owner)
            ->create();

        $fragmentable = FragmentableStub::create();
        $manager = ManagerFactory::make()
            ->withAssistants([FragmentAssistant::class])
            ->withModel($fragmentable)
            ->create();

        $this->assertCount(0, $this->fragmentRepo->getByOwner($owner));

        $this->asAdmin()->post(
            $manager->route('fragment-store', $owner, $fragmentable),
            [
                'order' => 1,
            ]
        );

        $this->assertCount(1, $this->fragmentRepo->getByOwner($owner));
    }

    /** @test */
    public function fragmentable_values_can_be_saved()
    {
        $owner = OwnerStub::create();
        ManagerFactory::make()
            ->withAssistants([FragmentAssistant::class])
            ->withModel($owner)
            ->create();

        $fragmentable = FragmentableStub::create();
        $manager = ManagerFactory::make()
            ->withAssistants([FragmentAssistant::class])
            ->withModel($fragmentable)
            ->create();

        $this->asAdmin()->post(
            $manager->route('fragment-store', $owner, $fragmentable),
            [
                'order' => 1,
                'title' => 'foobar',
            ]
        );

        $this->assertEquals('foobar', $this->fragmentRepo->getByOwner($owner)->first()->title);
    }
}
