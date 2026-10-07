<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Support\Facades\DB;
use Throwable;

final class AuditRecorder
{
    public function record(AuditEventDTO $event): AuditEvent
    {
        return DB::transaction(function () use ($event): AuditEvent {
            $record = AuditEvent::query()->create([
                ...$event->toRecord(),
                'recorded_at' => now()->utc(),
                'has_rich_data' => $event->richData !== [],
            ]);

            $links = [];
            foreach ($event->models->all() as $position => $model) {
                $links[] = AuditEventModel::query()->create([
                    ...$model->toRecord(),
                    'event_id' => $record->getKey(),
                    'has_rich_data' => collect($event->richData)->contains(fn (RichData $piece) => $piece->model === $position),
                ]);
            }

            foreach ($event->richData as $piece) {
                $limit = (int) config('chief.audit.rich_limits.'.$piece->type, match ($piece->type) {
                    'metadata', 'reference' => 8192, 'mailpreview' => 5242880, default => 262144,
                });
                $size = strlen($piece->content ?? '').strlen(json_encode($piece->metadata) ?: '');
                $base = [
                    'event_id' => $record->getKey(),
                    'model_link_id' => $piece->model === null ? null : $links[$piece->model]->getKey(),
                    'type' => $piece->type,
                ];

                if ($size > $limit || $limit <= 0) {
                    AuditRichData::query()->create([...$base, 'status' => 'limit']);

                    continue;
                }

                try {
                    DB::transaction(function () use ($base, $piece): void {
                        AuditRichData::query()->create([...$base, 'status' => 'available', 'content' => $piece->content,
                            'metadata' => $piece->metadata, 'disk' => $piece->disk, 'path' => $piece->path]);
                    });
                } catch (Throwable) {
                    AuditRichData::query()->create([...$base, 'status' => 'error']);
                }
            }

            return $record;
        });
    }
}
