<?php

namespace Thinktomorrow\Chief\Common\Audit;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\ActivityLogger;

class Audit extends Activity
{
    public static function activity(string $logName = null): ActivityLogger
    {
        $defaultLogName = config('activitylog.default_log_name');
        
        return app(ActivityLogger::class)->useLog($logName ?? $defaultLogName);
    }

    public static function getActivityFor(Model $subject)
    {
        return self::forSubject($subject)->get();
    }
}
