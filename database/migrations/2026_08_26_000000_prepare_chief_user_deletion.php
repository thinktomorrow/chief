<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Admin\Users\User;

return new class extends Migration
{
    private const INVITATIONS_TABLE = 'chief_users_invitations';

    private const USER_MORPH_TYPE = 'chiefuser';

    public function up(): void
    {
        Schema::rename('invitations', self::INVITATIONS_TABLE);

        Schema::table(self::INVITATIONS_TABLE, function (Blueprint $table) {
            $table->json('inviter_snapshot')->nullable()->after('inviter_id');
        });

        $this->backfillInviterSnapshots();
        $this->backfillAuditCauserSnapshots();
        $this->removeOrphanedInvitations();
        $this->normalizeAuditCauserTypes();

        Schema::table(self::INVITATIONS_TABLE, function (Blueprint $table) {
            $table->unsignedInteger('inviter_id')->nullable()->change();
            $table->json('inviter_snapshot')->nullable(false)->change();
        });

        Schema::table(self::INVITATIONS_TABLE, function (Blueprint $table) {
            $table->foreign('invitee_id')
                ->references('id')
                ->on('chief_users')
                ->cascadeOnDelete();
            $table->foreign('inviter_id')
                ->references('id')
                ->on('chief_users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table(self::INVITATIONS_TABLE, function (Blueprint $table) {
            $table->dropForeign(['invitee_id']);
            $table->dropForeign(['inviter_id']);
        });

        DB::table(self::INVITATIONS_TABLE)->whereNull('inviter_id')->delete();

        Schema::table(self::INVITATIONS_TABLE, function (Blueprint $table) {
            $table->unsignedInteger('inviter_id')->nullable(false)->change();
            $table->dropColumn('inviter_snapshot');
        });

        Schema::rename(self::INVITATIONS_TABLE, 'invitations');
    }

    private function backfillInviterSnapshots(): void
    {
        DB::table(self::INVITATIONS_TABLE)
            ->orderBy('id')
            ->chunkById(500, function ($invitations): void {
                $inviters = DB::table('chief_users')
                    ->whereIn('id', $invitations->pluck('inviter_id')->filter()->unique())
                    ->get()
                    ->keyBy('id');

                foreach ($invitations as $invitation) {
                    $inviter = $inviters->get($invitation->inviter_id);
                    $snapshot = $inviter
                        ? $this->userSnapshot($inviter)
                        : $this->unknownUserSnapshot((int) $invitation->inviter_id);

                    DB::table(self::INVITATIONS_TABLE)
                        ->where('id', $invitation->id)
                        ->update([
                            'inviter_snapshot' => json_encode($snapshot, JSON_THROW_ON_ERROR),
                        ]);
                }
            });
    }

    private function backfillAuditCauserSnapshots(): void
    {
        $activityTable = config('activitylog.table_name');

        DB::table($activityTable)
            ->whereIn('causer_type', $this->userMorphTypes())
            ->orderBy('id')
            ->chunkById(500, function ($activities) use ($activityTable): void {
                $users = DB::table('chief_users')
                    ->whereIn('id', $activities->pluck('causer_id')->filter()->unique())
                    ->get()
                    ->keyBy('id');

                foreach ($activities as $activity) {
                    $user = $users->get($activity->causer_id);
                    $snapshot = $user
                        ? $this->auditCauserSnapshot($user)
                        : $this->unknownAuditCauserSnapshot((int) $activity->causer_id);

                    $properties = json_decode($activity->properties ?? '[]', true, flags: JSON_THROW_ON_ERROR);
                    $properties = is_array($properties) ? $properties : [];
                    $properties['causer_snapshot'] = $snapshot;

                    DB::table($activityTable)
                        ->where('id', $activity->id)
                        ->update(['properties' => json_encode($properties, JSON_THROW_ON_ERROR)]);
                }
            });
    }

    private function removeOrphanedInvitations(): void
    {
        DB::table(self::INVITATIONS_TABLE)
            ->whereNotIn('invitee_id', DB::table('chief_users')->select('id'))
            ->delete();

        DB::table(self::INVITATIONS_TABLE)
            ->whereNotIn('inviter_id', DB::table('chief_users')->select('id'))
            ->update(['inviter_id' => null]);
    }

    private function normalizeAuditCauserTypes(): void
    {
        DB::table(config('activitylog.table_name'))
            ->whereIn('causer_type', $this->userMorphTypes())
            ->update(['causer_type' => self::USER_MORPH_TYPE]);
    }

    /**
     * @return array{id: int, firstname: string, lastname: string, fullname: string, email: string}
     */
    private function userSnapshot(object $user): array
    {
        return [
            'id' => (int) $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'fullname' => trim($user->firstname.' '.$user->lastname),
            'email' => $user->email,
        ];
    }

    /**
     * @return array{id: int, firstname: string, lastname: string, fullname: string}
     */
    private function auditCauserSnapshot(object $user): array
    {
        return [
            'id' => (int) $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'fullname' => trim($user->firstname.' '.$user->lastname),
        ];
    }

    /**
     * @return array{id: int, firstname: string, lastname: string, fullname: string, email: null}
     */
    private function unknownUserSnapshot(int $userId): array
    {
        return [
            'id' => $userId,
            'firstname' => 'Onbekende gebruiker',
            'lastname' => '',
            'fullname' => 'Onbekende gebruiker',
            'email' => null,
        ];
    }

    /**
     * @return array{id: int, firstname: string, lastname: string, fullname: string}
     */
    private function unknownAuditCauserSnapshot(int $userId): array
    {
        return [
            'id' => $userId,
            'firstname' => 'Onbekende gebruiker',
            'lastname' => '',
            'fullname' => 'Onbekende gebruiker',
        ];
    }

    /**
     * @return list<string>
     */
    private function userMorphTypes(): array
    {
        return [
            self::USER_MORPH_TYPE,
            User::class,
            'Thinktomorrow\\Chief\\Users\\User',
        ];
    }
};
