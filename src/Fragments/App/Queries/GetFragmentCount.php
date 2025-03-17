<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;

class GetFragmentCount
{
    private ContextRepository $contextRepository;

    public function __construct(ContextRepository $contextRepository)
    {
        $this->contextRepository = $contextRepository;
    }

    /**
     * Get the count of appearances of this fragment. This count shows
     * the amount of contexts in which this fragment is used.
     */
    public function get(string $fragmentId): int
    {
        return $this->contextRepository->getContextsByFragment($fragmentId)->count();
    }
}
