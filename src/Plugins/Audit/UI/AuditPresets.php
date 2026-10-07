<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\UI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Thinktomorrow\Chief\Forms\Layouts\Window;

final class AuditPresets
{
    public static function modelHistoryWindow(Model $model): iterable
    {
        if (! $model->exists || ! (Gate::allows('view-full-audit') || Gate::allows('view-related-audit'))) {
            return [];
        }

        return [Window::make('audit_model_history')
            ->position('aside')
            ->tag(['not-on-model-create', 'not-on-create', 'not-on-model-edit', 'not-on-edit'])
            ->setView('chief-audit::model-history', ['auditModel' => $model])];
    }
}
