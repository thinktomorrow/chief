<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Audit;

use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\Contracts\Activity as ActivityContract;

final class DisabledChiefActivityLogger extends ActivityLogger
{
    public function log(string $description): ?ActivityContract
    {
        return null;
    }
}
