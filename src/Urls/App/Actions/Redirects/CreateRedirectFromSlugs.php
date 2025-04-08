<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Actions\Redirects;

final class CreateRedirectFromSlugs
{
    private string $site;

    private string $redirectSlug;

    private string $targetSlug;

    public function __construct(string $site, string $redirectSlug, string $targetSlug)
    {
        $this->site = $site;
        $this->redirectSlug = $redirectSlug;
        $this->targetSlug = $targetSlug;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    public function getRedirectSlug(): string
    {
        return trim($this->redirectSlug, '/');
    }

    public function getTargetSlug(): string
    {
        return trim($this->targetSlug, '/');
    }
}
