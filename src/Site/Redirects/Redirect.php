<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Redirects;

use Thinktomorrow\Chief\Site\Urls\UrlRecord;

// TODO: WIP
final class Redirect
{
    private string $locale;
    private string $redirectUrl;
    private string $targetUrl;

    public function __construct(string $locale, string $redirectUrl, string $targetUrl)
    {
        $this->locale = $locale;
        $this->redirectUrl = $redirectUrl;
        $this->targetUrl = $targetUrl;
    }

    public static function fromUrlRecord(UrlRecord $record): static
    {
        return new static(
            $record->locale,
            $record->slug,
            // TODO: fetch target url
        );
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }
}
