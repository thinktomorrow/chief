<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Reading;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEvent;

final class RecentHistory
{
    public function __construct(private VisibleHistory $history) {}

    /**
     * @param  array{type?: string|list<string>, category?: string, actor?: string, model_type?: string, model_id?: string, from?: string, to?: string}  $filters
     * @return Collection<int, AuditEvent>
     */
    public function select(array $filters = [], int $limit = 5, bool $includeSecondary = false): Collection
    {
        return $this->history->recent($filters, max(1, min(20, $limit)), $includeSecondary);
    }
}
