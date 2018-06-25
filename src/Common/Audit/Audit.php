<?php

namespace Thinktomorrow\Chief\Common\Audit;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\ActivityLogger;
use Thinktomorrow\Chief\Users\User;

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

    public static function getActivityBy(User $causer)
    {
        return self::causedBy($causer)->get();
    }

    public static function getActivity()
    {
        return self::all()->sortByDesc('created_at');
    }
}
