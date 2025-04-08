<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Actions;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class ChangeHomepageUrl
{
    private ModelReference $modelReference;

    private string $site;

    public function __construct(ModelReference $modelReference, string $site)
    {
        $this->modelReference = $modelReference;
        $this->site = $site;
    }

    public function getModelReference(): ModelReference
    {
        return $this->modelReference;
    }

    public function getSite(): string
    {
        return $this->site;
    }
}
