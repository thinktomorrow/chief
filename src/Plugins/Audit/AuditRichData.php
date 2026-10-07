<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Database\Eloquent\Model;

final class AuditRichData extends Model
{
    protected $table = 'chief_audit_rich_data';

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }
}
