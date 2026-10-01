<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

final class AuditRecorder
{
    /**
     * @param  array{name: string, id?: string}  $actorSnapshot
     * @param  array{name: string}|null  $modelSnapshot
     */
    public function log(
        string $type,
        string $actorType,
        array $actorSnapshot,
        ?string $occurredAt = null,
        string $category = 'general',
        ?string $summary = null,
        ?string $outcome = null,
        ?string $modelType = null,
        ?string $modelId = null,
        ?array $modelSnapshot = null,
    ): AuditEvent {
        $data = Validator::make([
            'type' => $type,
            'actor_type' => $actorType,
            'actor_snapshot' => $actorSnapshot,
            'occurred_at' => $occurredAt,
            'category' => $category,
            'summary' => $summary,
            'outcome' => $outcome,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'model_snapshot' => $modelSnapshot,
        ], [
            'type' => ['required', 'string', 'max:190', 'regex:/^[a-z0-9][a-z0-9._-]*$/'],
            'category' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9][a-z0-9._-]*$/'],
            'outcome' => ['nullable', 'string', 'max:100'],
            'occurred_at' => ['nullable', 'date'],
            'summary' => ['nullable', 'string', 'max:500'],
            'actor_type' => ['required', 'in:admin,system,external'],
            'actor_snapshot' => ['required', 'array:name,id'],
            'actor_snapshot.name' => ['required', 'string', 'max:190'],
            'actor_snapshot.id' => ['nullable', 'string', 'max:190'],
            'model_type' => ['required_with:model_id,model_snapshot', 'nullable', 'string', 'max:190'],
            'model_id' => ['required_with:model_type,model_snapshot', 'nullable', 'string', 'max:190'],
            'model_snapshot' => ['required_with:model_type,model_id', 'nullable', 'array:name'],
            'model_snapshot.name' => ['required_with:model_snapshot', 'string', 'max:190'],
        ])->validate();

        return AuditEvent::query()->create([
            ...$data,
            'occurred_at' => Carbon::parse($data['occurred_at'] ?? now())->utc(),
            'recorded_at' => now()->utc(),
        ]);
    }
}
