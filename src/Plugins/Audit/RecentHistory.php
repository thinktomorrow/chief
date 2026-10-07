<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Support\Collection;

final class RecentHistory
{
    public function __construct(private VisibleHistory $history) {}

    /**
     * @param  array{type?: string|list<string>, category?: string, actor?: string, model_type?: string, model_id?: string, from?: string, to?: string}  $filters
     * @return Collection<int, AuditEvent>
     */
    public function select(array $filters = [], int $limit = 5, bool $includeSecondary = false): Collection
    {
        $events = $this->history->select($filters);

        if (! $includeSecondary) {
            $events = $events->filter(fn (AuditEvent $event) => $this->history->priority($event) !== 'secondary');
        }

        return $events->take(max(1, min(20, $limit)))->values();
    }
}
