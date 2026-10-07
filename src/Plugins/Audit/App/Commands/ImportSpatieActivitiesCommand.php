<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use JsonException;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditEventDTO;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditModelDTO;

final class ImportSpatieActivitiesCommand extends Command
{
    private const REGISTRY = 'chief_audit_spatie_imports';

    protected $signature = 'chief-audit:import-spatie {--batch=500 : Number of source rows per batch} {--cleanup-registry : Verify completeness and remove the temporary source-ID registry}';

    protected $description = 'Import historical Spatie activities into Chief audit; verify before removing the temporary registry';

    public function handle(Registry $resources): int
    {
        $source = config('activitylog.table_name', 'activity_log');

        if (! Schema::hasTable($source)) {
            $this->error('Spatie source table is missing.');

            return self::FAILURE;
        }

        if ($this->option('cleanup-registry')) {
            if (! Schema::hasTable(self::REGISTRY) || ! $this->isComplete($source)) {
                $this->error('Import registry is missing or incomplete; registry retained.');

                return self::FAILURE;
            }

            Schema::drop(self::REGISTRY);
            $this->info('Import verified; temporary registry removed. The Spatie table remains intact.');

            return self::SUCCESS;
        }

        $batch = filter_var($this->option('batch'), FILTER_VALIDATE_INT);
        if ($batch === false || $batch < 1 || $batch > 5000) {
            $this->error('Batch must be between 1 and 5000.');

            return self::FAILURE;
        }

        if (! Schema::hasTable(self::REGISTRY)) {
            if (DB::table('chief_audit_events')->where('type', 'legacy.spatie')->exists()) {
                $this->error('Legacy events already exist without a registry; refusing to duplicate the import.');

                return self::FAILURE;
            }

            Schema::create(self::REGISTRY, function (Blueprint $table): void {
                $table->unsignedBigInteger('source_id')->primary();
                $table->foreignId('event_id')->unique()->constrained('chief_audit_events')->cascadeOnDelete();
            });
        }

        $modelTypes = [];
        foreach ($resources->resources() as $resource) {
            $class = $resource::modelClassName();
            $morphType = (new $class)->getMorphClass();
            $modelTypes[$morphType] = $morphType;
            $modelTypes[$class] = $morphType;
        }

        try {
            DB::table($source)->orderBy('id')->chunkById($batch, function ($activities) use ($modelTypes): void {
                foreach ($activities as $activity) {
                    DB::transaction(function () use ($activity, $modelTypes): void {
                        if (DB::table(self::REGISTRY)->where('source_id', $activity->id)->exists()) {
                            return;
                        }

                        $event = $this->import($activity, $modelTypes);
                        DB::table(self::REGISTRY)->insert(['source_id' => $activity->id, 'event_id' => $event]);
                    });
                }
            }, 'id');
        } catch (\Throwable $exception) {
            $this->error('Import stopped: '.$exception->getMessage().'. Rerun to resume.');

            return self::FAILURE;
        }

        if (! $this->isComplete($source)) {
            $this->error('Import incomplete; rerun to resume.');

            return self::FAILURE;
        }

        $this->info('Import complete and verified. The Spatie table and temporary registry remain intact. Use --cleanup-registry after verification.');

        return self::SUCCESS;
    }

    /** @param array<string, string> $modelTypes */
    private function import(object $activity, array $modelTypes): int
    {
        if ($activity->created_at === null) {
            throw new \UnexpectedValueException('Source activity '.$activity->id.' has no created_at timestamp');
        }

        try {
            $properties = $activity->properties === null ? null : json_decode($activity->properties, true, 64, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new \UnexpectedValueException('Invalid properties on activity '.$activity->id, previous: $exception);
        }

        $admin = in_array($activity->causer_type, ['chiefuser', User::class, 'Thinktomorrow\\Chief\\Users\\User'], true);
        $actorType = $admin ? 'admin' : ($activity->causer_type === null ? 'system' : 'external');
        $name = match ($actorType) {
            'admin' => 'Onbekende admin', 'system' => 'Systeem', default => 'Onbekende actor',
        };
        $actor = ['name' => $name];
        if ($activity->causer_id !== null) {
            $actor['id'] = (string) $activity->causer_id;
        }

        $models = [];
        if ($activity->subject_id !== null && isset($modelTypes[$activity->subject_type])) {
            $models[] = new AuditModelDTO($modelTypes[$activity->subject_type], (string) $activity->subject_id, ['name' => 'Legacy model']);
        }

        $legacy = [
            'log_name' => $activity->log_name,
            'event' => $activity->event,
            'description' => $activity->description,
            'subject_type' => $activity->subject_type,
            'subject_id' => $activity->subject_id === null ? null : (string) $activity->subject_id,
            'causer_type' => $activity->causer_type,
            'causer_id' => $activity->causer_id === null ? null : (string) $activity->causer_id,
            'properties' => $properties,
            'created_at' => (string) $activity->created_at,
            'updated_at' => $activity->updated_at === null ? null : (string) $activity->updated_at,
        ];
        AuditEventDTO::assertContext(['legacy' => $legacy]);

        return (int) History::log(
            type: 'legacy.spatie', actorType: $actorType, actorSnapshot: $actor,
            occurredAt: (string) $activity->created_at, category: 'legacy',
            summary: mb_substr((string) $activity->description, 0, 500),
            context: ['legacy' => $legacy], models: $models,
        )->getKey();
    }

    private function isComplete(string $source): bool
    {
        $missing = DB::table($source.' as source')
            ->leftJoin(self::REGISTRY.' as imported', 'source.id', '=', 'imported.source_id')
            ->whereNull('imported.source_id')->exists();
        $orphaned = DB::table(self::REGISTRY.' as imported')
            ->leftJoin('chief_audit_events as events', 'imported.event_id', '=', 'events.id')
            ->leftJoin($source.' as source', 'imported.source_id', '=', 'source.id')
            ->where(fn ($query) => $query->whereNull('events.id')->orWhereNull('source.id'))->exists();

        return ! $missing && ! $orphaned
            && DB::table($source)->count() === DB::table(self::REGISTRY)->count();
    }
}
