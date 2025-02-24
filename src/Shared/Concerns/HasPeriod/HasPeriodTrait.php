<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\HasPeriod;

use Illuminate\Support\Carbon;

trait HasPeriodTrait
{
    protected static function bootHasPeriodTrait()
    {
        static::addGlobalScope(new SortPeriodDateScope);
    }

    public function initializeHasPeriodTrait()
    {
        $this->casts = array_merge($this->casts ?? [], [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ]);
    }

    public function scopePassed($query)
    {
        return $query->where('end_at', '<', Carbon::now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_at', '>', Carbon::now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_at', '<', Carbon::now())->where('end_at', '>', Carbon::now());
    }

    public function saveStartAtField($start_at)
    {
        $this->start_at = Carbon::parse($start_at)->startOfDay();
        $this->end_at = $this->start_at->endOfDay();

        $this->save();
    }

    public function saveEndAtField($end_at)
    {
        if ($end_at) {
            $this->end_at = Carbon::parse($end_at)->endOfDay();
            $this->save();
        }
    }
}
