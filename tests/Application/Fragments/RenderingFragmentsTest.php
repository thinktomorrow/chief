<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\App\Providers\ChiefProjectServiceProvider;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\FragmentableStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\OwnerStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class RenderingFragmentsTest extends ChiefTestCase
{
    /** @var FragmentRepository */
    private $fragmentRepo;

    protected function setUp(): void
    {
        parent::setUp();

        (new ChiefProjectServiceProvider(app()))->boot();

        OwnerStub::migrateUp();
        FragmentableStub::migrateUp();

        $this->fragmentRepo = app(FragmentRepository::class);
    }

    /** @test */
    public function fragments_can_be_rendered()
    {
        $owner = OwnerStub::create();
        ManagerFactory::make()
            ->withAssistants([FragmentAssistant::class])
            ->withModel($owner)
            ->create();

        $manager = ManagerFactory::make()
            ->withAssistants([FragmentAssistant::class])
            ->withModel(FragmentableStub::class)
            ->create();

        $this->asAdmin()->post(
            $manager->route('fragment-store', $owner, FragmentableStub::managedModelKey()),
            [
                'order' => 1,
            ]
        );

        $this->asAdmin()->post(
            $manager->route('fragment-store', $owner, FragmentableStub::managedModelKey()),
            [
                'order' => 2,
            ]
        );

        $this->assertRenderedFragments($owner, 'fragment-stub-1 fragment-stub-2 ');
    }

    /** @test */
    public function a_model_can_render_a_static_fragmentable()
    {
        $owner = OwnerStub::create();
        ManagerFactory::make()
            ->withAssistants([FragmentAssistant::class])
            ->withModel($owner)
            ->create();

        app(Register::class)->staticFragment(SnippetStub::class);

        $manager = app(Registry::class)->manager(SnippetStub::managedModelKey());

        $this->asAdmin()->post(
            $manager->route('fragment-store', $owner, SnippetStub::managedModelKey()),
            [
                'order' => 1,
            ]
        );

        $this->assertRenderedFragments($owner, 'snippet-stub');
    }

    /** @test */
    public function no_fragments_render_an_empty_string()
    {
        $owner = OwnerStub::create();

        $this->assertRenderedFragments($owner, '');
    }
}
