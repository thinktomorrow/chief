<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Actions;

use Thinktomorrow\Chief\Urls\Models\LinkStatus;

final class UpdateUrl
{
    private string $id;

    private string $slug;

    private string $status;

    private bool $prependBaseUrlSegment;

    /** Avoids creating empty or root slash slug */
    private bool $allowHomepageSlug;

    public function __construct(string $id, string $slug, string $status, bool $prependBaseUrlSegment = true, bool $allowHomepageSlug = false)
    {
        $this->id = $id;
        $this->slug = $slug;
        $this->status = $status;
        $this->prependBaseUrlSegment = $prependBaseUrlSegment;
        $this->allowHomepageSlug = $allowHomepageSlug;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getStatus(): LinkStatus
    {
        return LinkStatus::from($this->status);
    }

    public function prependBaseUrlSegment(): bool
    {
        return $this->prependBaseUrlSegment;
    }

    public function allowHomepageSlug(): bool
    {
        return $this->allowHomepageSlug;
    }
}
