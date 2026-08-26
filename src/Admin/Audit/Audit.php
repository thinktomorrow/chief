<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Audit;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\Models\Activity;
use Thinktomorrow\Chief\Admin\Users\User;

class Audit extends Activity
{
    private const CAUSER_SNAPSHOT_PROPERTY = 'causer_snapshot';

    public function getReadableSubject()
    {
        return Str::contains($this->subject_type, '\\')
            ? substr($this->subject_type, strrpos($this->subject_type, '\\') + 1)
            : $this->subject_type;
    }

    public function getReadableCreatedAt()
    {
        if ($this->created_at->gte(now()->subDays(6))) {
            return $this->created_at->locale(app()->getLocale())->diffForHumans();
        }

        return $this->created_at->format('d/m/Y H:i');
    }

    public static function activity(?string $logName = null): ActivityLogger
    {
        $defaultLogName = config('activitylog.default_log_name');

        $activity = app(ActivityLogger::class)->useLog($logName ?? $defaultLogName);
        $causer = auth()->guard('chief')->user();

        if (! $causer instanceof User) {
            return $activity;
        }

        return $activity
            ->causedBy($causer)
            ->withProperty(self::CAUSER_SNAPSHOT_PROPERTY, [
                'id' => (int) $causer->id,
                'firstname' => $causer->firstname,
                'lastname' => $causer->lastname,
                'fullname' => $causer->fullname,
            ]);
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

    public static function getPaginatedAuditByCauserId(int $causerId, int $perPage = 50): Paginator
    {
        return static::query()
            ->where('causer_type', (new User)->getMorphClass())
            ->where('causer_id', $causerId)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }

    /**
     * @return array{id: int, firstname: string, lastname: string, fullname: string}|null
     */
    public static function findCauserSnapshot(int $causerId): ?array
    {
        $activity = static::query()
            ->where('causer_type', (new User)->getMorphClass())
            ->where('causer_id', $causerId)
            ->whereNotNull('properties->'.self::CAUSER_SNAPSHOT_PROPERTY)
            ->oldest('created_at')
            ->first();

        return $activity?->causerSnapshot();
    }

    /**
     * @return array{id: int, firstname: string, lastname: string, fullname: string}|null
     */
    public function causerSnapshot(): ?array
    {
        $snapshot = $this->properties?->get(self::CAUSER_SNAPSHOT_PROPERTY);

        return is_array($snapshot) ? $snapshot : null;
    }

    public function causerName(): ?string
    {
        return $this->causerSnapshot()['fullname'] ?? null;
    }
}
