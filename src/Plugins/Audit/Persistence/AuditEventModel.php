<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Persistence;

use Illuminate\Database\Eloquent\Model;

final class AuditEventModel extends Model
{
    protected $table = 'chief_audit_event_models';

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'event_id' => 'integer',
            'model_snapshot' => 'array',
            'context' => 'array',
            'changes' => 'array',
        ];
    }
}
