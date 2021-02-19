<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Audit;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\Models\Activity;
use Thinktomorrow\Chief\Admin\Users\User;

class Audit extends Activity
{
    public $with = ['causer'];

    public static function activity(string $logName = null): ActivityLogger
    {
        $defaultLogName = config('activitylog.default_log_name');

        return app(ActivityLogger::class)->useLog($logName ?? $defaultLogName);
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
