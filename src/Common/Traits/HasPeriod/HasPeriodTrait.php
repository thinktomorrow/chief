<?php

namespace Thinktomorrow\Chief\Common\Traits\HasPeriod;

use Illuminate\Support\Carbon;

trait HasPeriodTrait
{
    protected static function bootHasPeriodTrait()
    {
        static::addGlobalScope(new SortPeriodDateScope);
    }

    public function __construct(array $attributes = [])
    {
        $this->dates = array_merge($this->dates, ['start_at', 'end_at']);

        parent::__construct($attributes);
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
        $this->start_at = Carbon::parse($start_at);
        $this->save();
    }

    public function saveEndAtField($end_at)
    {
        $this->end_at = Carbon::parse($end_at);
        $this->save();
    }
}
