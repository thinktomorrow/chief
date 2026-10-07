<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class AuditEvent extends Model
{
    protected $table = 'chief_audit_events';

    public $timestamps = false;

    protected $guarded = [];

    public function models(): HasMany
    {
        return $this->hasMany(AuditEventModel::class, 'event_id');
    }

    protected function casts(): array
    {
        return [
            'actor_snapshot' => 'array',
            'model_snapshot' => 'array',
            'context' => 'array',
            'occurred_at' => 'immutable_datetime',
            'recorded_at' => 'immutable_datetime',
        ];
    }
}
