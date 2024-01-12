<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Resource\Models;

use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentRepositoryTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
    }

    public function test_it_returns_empty_collection_by_default()
    {
        $fragments = app(FragmentRepository::class)->getByOwner($this->owner, 'nl');

        $this->assertCount(0, $fragments);
    }

    public function test_it_can_get_fragments_by_owner_context()
    {
        $context = ContextModel::create([
            'owner_type' => $this->owner->getMorphClass(),
            'owner_id' => $this->owner->id,
            'locale' => 'nl',
        ]);

        $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner, 'nl');
        $this->assertCount(1, $fragments);
    }

    public function test_it_cannot_get_fragments_by_other_context()
    {
        $context = ContextModel::create([
            'owner_type' => $this->owner->getMorphClass(),
            'owner_id' => $this->owner->id,
            'locale' => 'nl',
        ]);

        $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $this->assertCount(1, app(FragmentRepository::class)->getByOwner($this->owner, 'nl'));
        $this->assertCount(0, app(FragmentRepository::class)->getByOwner($this->owner, 'fr'));
    }

}
