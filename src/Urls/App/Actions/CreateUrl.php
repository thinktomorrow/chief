<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

final class CreateUrl
{
    private ModelReference $modelReference;

    private string $site;

    private string $slug;

    private string $status;

    private bool $prependBaseUrlSegment;

    public function __construct(ModelReference $modelReference, string $site, string $slug, string $status, bool $prependBaseUrlSegment = true)
    {
        $this->modelReference = $modelReference;
        $this->site = $site;
        $this->slug = $slug;
        $this->status = $status;
        $this->prependBaseUrlSegment = $prependBaseUrlSegment;
    }

    public function getModelReference(): ModelReference
    {
        return $this->modelReference;
    }

    public function getSite(): string
    {
        return $this->site;
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
}
