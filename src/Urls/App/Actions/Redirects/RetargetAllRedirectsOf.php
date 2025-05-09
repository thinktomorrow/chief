<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Actions\Redirects;

final class RetargetAllRedirectsOf
{
    private string $recordId;

    private string $targetId;

    public function __construct(string $recordId, string $targetId)
    {
        $this->recordId = $recordId;
        $this->targetId = $targetId;
    }

    public function getRecordId(): string
    {
        return $this->recordId;
    }

    public function getTargetId(): string
    {
        return $this->targetId;
    }
}
