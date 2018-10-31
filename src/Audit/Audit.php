<?php

namespace Thinktomorrow\Chief\Audit;

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

    public static function getAllActivityFor(Model $subject)
    {
        return self::allActivityFor($subject)->get();
    }

    public static function ScopeAllActivityFor(Builder $query, Model $subject): Builder
    {
        return $query
            ->where('subject_type', $subject->getMorphClass());
    }

    public static function getActivityBy(User $causer)
    {
        return self::causedBy($causer)->get()->sortByDesc('created_at');
    }

    public static function getActivity()
    {
        return self::all()->sortByDesc('created_at');
    }
}
