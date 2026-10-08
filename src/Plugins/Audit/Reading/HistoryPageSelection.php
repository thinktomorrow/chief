<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Reading;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEvent;

/**
 * Selects a page from events that have already been projected for the current viewer.
 */
final class HistoryPageSelection
{
    /** @var Collection<int, AuditEvent> */
    private Collection $primaryPage;

    /** @var Collection<int, AuditEvent> */
    private Collection $secondaryPage;

    /** @var Collection<int, AuditEvent> */
    private Collection $secondariesInWindow;

    private int $primaryTotal = 0;

    private int $secondaryTotal = 0;

    public function __construct(
        private int $perPage,
        private int $page,
        private bool $hasActiveFilters,
        private bool $showAll,
    ) {
        $this->primaryPage = collect();
        $this->secondaryPage = collect();
        $this->secondariesInWindow = collect();
    }

    /** Filtered timelines page all matching events; otherwise only primary events define the page. */
    public function accept(AuditEvent $event, bool $isSecondary): void
    {
        if ($this->hasActiveFilters || ! $isSecondary) {
            if ($this->isOnPage($this->primaryTotal)) {
                $this->primaryPage->push($event);
            }

            $this->primaryTotal++;

            return;
        }

        if ($this->isOnPage($this->secondaryTotal)) {
            $this->secondaryPage->push($event);
        }

        $this->secondaryTotal++;
    }

    public function needsSecondaryWindow(): bool
    {
        return ! $this->hasActiveFilters && $this->showAll && $this->primaryPage->isNotEmpty();
    }

    /** Add secondary events between the primary page's newest and oldest events. */
    public function acceptSecondaryInWindow(AuditEvent $event, bool $isSecondary): void
    {
        if (! $isSecondary) {
            return;
        }

        $newest = $this->primaryPage->first();
        $oldest = $this->primaryPage->last();

        if ($this->notLaterThan($event, $newest) && $this->notLaterThan($oldest, $event)) {
            $this->secondariesInWindow->push($event);
        }
    }

    /**
     * Fall back to a secondary-only page when there are no primary events at all.
     *
     * @return Collection<int, AuditEvent>
     */
    public function items(): Collection
    {
        if (! $this->hasActiveFilters && $this->showAll && $this->primaryTotal === 0) {
            return $this->secondaryPage;
        }

        if ($this->secondariesInWindow->isEmpty()) {
            return $this->primaryPage;
        }

        return $this->primaryPage
            ->concat($this->secondariesInWindow)
            ->sort(fn (AuditEvent $a, AuditEvent $b) => $this->notLaterThan($a, $b) ? 1 : -1)
            ->values();
    }

    /** Secondary events in a primary window do not count toward the number of pages. */
    public function total(): int
    {
        return ! $this->hasActiveFilters && $this->showAll && $this->primaryTotal === 0
            ? $this->secondaryTotal
            : $this->primaryTotal;
    }

    private function isOnPage(int $index): bool
    {
        $offset = ($this->page - 1) * $this->perPage;

        return $index >= $offset && $index < $offset + $this->perPage;
    }

    private function notLaterThan(AuditEvent $left, AuditEvent $right): bool
    {
        return $left->occurred_at < $right->occurred_at || $left->occurred_at == $right->occurred_at && $left->getKey() <= $right->getKey();
    }
}
