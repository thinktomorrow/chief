<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Audit;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\Models\Activity;
use Thinktomorrow\Chief\Admin\Users\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;

class Audit extends Activity
{
    public $with = ['causer'];

    public function getReadableSubject()
    {
        return Str::contains($this->subject_type, '\\')
            ? substr($this->subject_type, strrpos($this->subject_type, '\\') + 1)
            : $this->subject_type;
    }
    public function getReadableCreatedAt()
    {
        if($this->created_at->gte(now()->subDays(6))) {
            return $this->created_at->locale(app()->getLocale())->diffForHumans();
        }

        return $this->created_at->format('d/m/Y H:i');
    }

    public static function activity(string $logName = null): ActivityLogger
    {
        $defaultLogName = config('activitylog.default_log_name');

        return app(ActivityLogger::class)->useLog($logName ?? $defaultLogName);
    }

    public static function getAllActivityFor(Model $subject)
    {
        return self::allActivityFor($subject)->get();
    }

    public static function scopeAllActivityFor(Builder $query, Model $subject): Builder
    {
        return $query
            ->where('subject_type', $subject->getMorphClass());
    }

    public static function getPaginatedAudit(int $perPage = 50): Paginator
    {
        return static::orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public static function getPaginatedAuditBy(User $causer, int $perPage = 50): Paginator
    {
        return static::causedBy($causer)->orderBy('created_at', 'DESC')->paginate($perPage);
    }
}
