<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Database\Eloquent\Model;

final class AuditEvent extends Model
{
    protected $table = 'chief_audit_events';

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'actor_snapshot' => 'array',
            'model_snapshot' => 'array',
            'occurred_at' => 'immutable_datetime',
            'recorded_at' => 'immutable_datetime',
        ];
    }
}
