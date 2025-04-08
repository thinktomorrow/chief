<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Actions;

use Thinktomorrow\Chief\Urls\Models\LinkStatus;

final class UpdateUrl
{
    private string $id;

    private string $slug;

    private string $status;

    public function __construct(string $id, string $slug, string $status)
    {
        $this->id = $id;
        $this->slug = $slug;
        $this->status = $status;
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
}
