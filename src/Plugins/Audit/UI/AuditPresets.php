<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\UI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Thinktomorrow\Chief\Forms\Layouts\Window;
use Thinktomorrow\Chief\Plugins\Audit\Reading\VisibleHistory;

final class AuditPresets
{
    public static function modelHistoryWindow(Model $model): iterable
    {
        if (! $model->exists || ! Gate::allows('view-audit')) {
            return [];
        }

        $history = app(VisibleHistory::class);
        $expanded = request()->query('audit_history') === 'all';
        $selection = $history->modelHistory($model->getMorphClass(), (string) $model->getKey(), $expanded);

        return [Window::make('audit_model_history')
            ->position('aside')
            ->tag(['not-on-model-create', 'not-on-create', 'not-on-model-edit', 'not-on-edit'])
            ->setView('chief-audit::model-history', [
                'history' => $history,
                'events' => $selection['events'],
                'hasMore' => $selection['hasMore'],
                'expanded' => $expanded,
            ])];
    }
}
