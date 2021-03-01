<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\App\Providers\ChiefProjectServiceProvider;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\StaticFragmentManager;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentsOwningAssistant;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\FragmentableStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\OwnerStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class StaticFragmentCrudTest extends ChiefTestCase
{
    /** @var FragmentRepository */
    private $fragmentRepo;

    private FragmentsOwner $owner;
    private Fragmentable $fragmentable;
    private StaticFragmentManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        (new ChiefProjectServiceProvider(app()))->boot();

        OwnerStub::migrateUp();
        FragmentableStub::migrateUp();

        $this->fragmentRepo = app(FragmentRepository::class);

        $this->owner = OwnerStub::create();
        ManagerFactory::make()
            ->withAssistants([FragmentsOwningAssistant::class])
            ->withModel($this->owner)
            ->create();

        $this->fragmentable = new SnippetStub();

        app(Register::class)->staticFragment(get_class($this->fragmentable));

        $this->manager = app(Registry::class)->manager($this->fragmentable::managedModelKey());
    }

    /** @test */
    public function static_fragmentable_can_be_created()
    {
        $this->asAdmin()->get($this->manager->route('fragment-create', $this->owner))
            ->assertStatus(200);
    }

    /** @test */
    public function static_fragmentable_can_be_stored()
    {
        $this->asAdmin()->post(
            $this->manager->route('fragment-store', $this->owner),
            [
                'order' => 1,
                'title' => 'foobar',
            ]
        );

        $this->assertEquals('foobar', $this->fragmentRepo->getByOwner($this->owner)->first()->getTitle());
    }

    /** @test */
    public function a_static_fragmentable_can_be_stored_without_values()
    {
        $this->assertCount(0, $this->fragmentRepo->getByOwner($this->owner));

        $this->asAdmin()->post(
            $this->manager->route('fragment-store', $this->owner),
            [
                'order' => 1,
            ]
        );

        $this->assertCount(1, $this->fragmentRepo->getByOwner($this->owner));
    }

    /** @test */
    public function static_fragmentable_can_be_edited()
    {
        $this->disableExceptionHandling();
        // Create fragment
        $this->asAdmin()->post(
            $this->manager->route('fragment-store', $this->owner),
            [
                'order' => 1,
                'title' => 'foobar',
            ]
        );

        $fragmentable = $this->fragmentRepo->getByOwner($this->owner)->first();

        $this->asAdmin()
            ->get($this->manager->route('fragment-edit', $fragmentable))
            ->assertStatus(200);
    }

    /** @test */
    public function static_fragmentable_can_be_updated()
    {
        // Create fragment
        $this->asAdmin()->post(
            $this->manager->route('fragment-store', $this->owner),
            [
                'order' => 1,
                'title' => 'foobar',
            ]
        );

        $fragmentable = $this->fragmentRepo->getByOwner($this->owner)->first();

        $this->asAdmin()->put(
            $this->manager->route('fragment-update', $fragmentable),
            [
                'order' => 1,
                'title' => 'foobar updated',
            ]
        );

        $fragmentable = $this->fragmentRepo->getByOwner($this->owner)->first();

        $this->assertEquals('foobar updated', $this->fragmentRepo->getByOwner($this->owner)->first()->getTitle());
    }
}
