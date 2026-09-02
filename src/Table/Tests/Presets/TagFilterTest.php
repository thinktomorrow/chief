<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Presets;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagReadRepository;
use Thinktomorrow\Chief\Table\Filters\Presets\TagFilter;
use Thinktomorrow\Chief\Table\Tests\TestCase;

final class TagFilterTest extends TestCase
{
    public function test_it_fetches_tags_once_when_options_are_requested_multiple_times(): void
    {
        $repository = new class implements TagReadRepository
        {
            public int $getAllCalls = 0;

            public function getAll(): Collection
            {
                $this->getAllCalls++;

                return collect();
            }

            public function getAllGroups(): Collection
            {
                return collect();
            }

            public function getAllForSelect(): array
            {
                return [];
            }

            public function getAllGroupsForSelect(): array
            {
                return [];
            }
        };

        app()->instance(TagReadRepository::class, $repository);

        $filter = TagFilter::makeDefault('article');

        $this->assertSame([], $filter->getOptions());
        $this->assertSame([], $filter->getOptions());
        $this->assertSame(1, $repository->getAllCalls);
    }
}
