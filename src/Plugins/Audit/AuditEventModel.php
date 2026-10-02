<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Database\Eloquent\Model;

final class AuditEventModel extends Model
{
    protected $table = 'chief_audit_event_models';

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'model_snapshot' => 'array',
            'context' => 'array',
        ];
    }
}
