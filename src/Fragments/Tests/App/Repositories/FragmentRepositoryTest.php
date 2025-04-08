<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;

class FragmentRepositoryTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private FragmentRepository $fragmentRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        $this->fragmentRepository = app(FragmentRepository::class);

        chiefRegister()->fragment(Hero::class);
    }

    public function test_it_returns_empty_collection_by_default(): void
    {
        $context = FragmentTestHelpers::createContext($this->owner);

        $fragments = $this->fragmentRepository->getByContext($context->id);

        $this->assertIsIterable($fragments);
        $this->assertCount(0, $fragments);
    }

    public function test_it_can_get_fragments_by_context(): void
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);

        $fragments = $this->fragmentRepository->getByContext($context->id);

        $this->assertCount(1, $fragments);
        $this->assertInstanceOf(Fragment::class, $fragments->first());
        $this->assertEquals($fragment->id, $fragments->first()->getFragmentId());
    }

    public function test_it_can_get_fragment_ids_by_context(): void
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);

        $ids = $this->fragmentRepository->getFragmentIdsByContext($context->id);

        $this->assertEquals([$fragment->id], $ids);
    }

    public function test_it_finds_fragment_in_fragment_collection(): void
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);

        $found = $this->fragmentRepository->findInFragmentCollection($context->id, $fragment->id);

        $this->assertInstanceOf(Fragment::class, $found);
        $this->assertEquals($fragment->id, $found->getFragmentId());
    }

    public function test_it_finds_fragment_in_context(): void
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);

        $found = $this->fragmentRepository->findInContext($fragment->id, $context->id);

        $this->assertInstanceOf(Fragment::class, $found);
        $this->assertEquals($fragment->id, $found->getFragmentId());
    }

    public function test_it_throws_exception_if_fragment_not_found_in_context(): void
    {
        $this->expectException(\Exception::class);

        $context = FragmentTestHelpers::createContext($this->owner);
        $this->fragmentRepository->findInContext('nonexistent', $context->id);
    }

    public function test_it_can_check_if_fragment_exists(): void
    {
        $fragment = FragmentModel::create(['id' => 'xxx-xxx-xxx-xxx', 'key' => 'hero']);

        $this->assertTrue($this->fragmentRepository->exists($fragment->id));
        $this->assertFalse($this->fragmentRepository->exists('nonexistent'));
    }

    public function test_it_can_find_fragment_by_id(): void
    {
        $fragment = FragmentModel::create(['id' => 'xxx-xxx-xxx-xxx', 'key' => 'hero']);

        $found = $this->fragmentRepository->find($fragment->id);

        $this->assertInstanceOf(Fragment::class, $found);
        $this->assertEquals($fragment->id, $found->getFragmentId());
    }

    public function test_it_throws_exception_when_context_does_not_exist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->fragmentRepository->getByContext('nonexistent');
    }

    public function test_it_can_generate_unique_fragment_id(): void
    {
        $generated = $this->fragmentRepository->nextId();

        $this->assertIsString($generated);
        $this->assertFalse(FragmentModel::where('id', $generated)->exists());

        // Simulate existing ID to test loop
        FragmentModel::create(['id' => $generated, 'key' => 'hero']);
        $newId = $this->fragmentRepository->nextId();

        $this->assertNotEquals($generated, $newId);
    }

    public function test_it_can_return_only_child_nodes_when_fragment_id_given(): void
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $parent = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);
        $child = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id, $parent->id);

        $children = $this->fragmentRepository->getFragmentCollection($context->id, $parent->id);

        $this->assertCount(1, $children);
        $this->assertEquals($child->id, $children->first()->getFragmentId());
    }
}
