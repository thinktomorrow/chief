<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class AuditRecorder
{
    /**
     * @param  array{type: string, category: string, summary: string, actor_type: string, actor_snapshot: array{name: string, id?: string}, outcome?: string, occurred_at?: string, model_type?: string, model_id?: string, model_snapshot?: array{name: string}}  $event
     */
    public function log(array $event): AuditEvent
    {
        $data = Validator::make($event, [
            'type' => ['required', 'string', 'max:190', 'regex:/^[a-z0-9][a-z0-9._-]*$/'],
            'category' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9][a-z0-9._-]*$/'],
            'outcome' => ['nullable', 'string', 'max:100'],
            'occurred_at' => ['sometimes', 'required', 'date'],
            'summary' => ['required', 'string', 'max:500'],
            'actor_type' => ['required', 'in:admin,system,external'],
            'actor_snapshot' => ['required', 'array:name,id'],
            'actor_snapshot.name' => ['required', 'string', 'max:190'],
            'actor_snapshot.id' => ['nullable', 'string', 'max:190'],
            'model_type' => ['required_with:model_id,model_snapshot', 'string', 'max:190'],
            'model_id' => ['required_with:model_type,model_snapshot', 'string', 'max:190'],
            'model_snapshot' => ['required_with:model_type,model_id', 'array:name'],
            'model_snapshot.name' => ['required_with:model_snapshot', 'string', 'max:190'],
        ])->validate();

        if (array_diff(array_keys($event), array_keys($data))) {
            throw ValidationException::withMessages(['event' => 'Unknown audit fields are not allowed.']);
        }

        return AuditEvent::query()->create([
            ...$data,
            'occurred_at' => Carbon::parse($data['occurred_at'] ?? now())->utc(),
            'recorded_at' => now()->utc(),
        ]);
    }
}
