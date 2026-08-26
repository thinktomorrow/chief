<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Users;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Admin\Users\Invites\Invitation;
use Thinktomorrow\Chief\App\Notifications\InvitationMail;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class DeleteUserTest extends ChiefTestCase
{
    public function test_admin_can_delete_a_user_and_related_account_data(): void
    {
        $user = $this->author();
        $admin = $this->admin();
        $otherInvitee = $this->author();
        $user->givePermissionTo('view-user');
        $invitationReceivedByUser = Invitation::make($user, $admin);
        $invitationSentByUser = Invitation::make($otherInvitee, $user);

        DB::table('chief_password_resets')->insert([
            'email' => $user->email,
            'token' => 'token',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin, 'chief')
            ->delete(route('chief.back.users.destroy', $user->id));

        $response->assertRedirect(route('chief.back.users.index'))
            ->assertSessionHas('messages.success');

        $this->assertDatabaseMissing('chief_users', ['id' => $user->id]);
        $this->assertDatabaseMissing('model_has_roles', [
            'model_id' => $user->id,
            'model_type' => $user->getMorphClass(),
        ]);
        $this->assertDatabaseMissing('model_has_permissions', [
            'model_id' => $user->id,
            'model_type' => $user->getMorphClass(),
        ]);
        $this->assertDatabaseMissing('chief_password_resets', ['email' => $user->email]);
        $this->assertDatabaseMissing('chief_users_invitations', ['id' => $invitationReceivedByUser->id]);
        $this->assertDatabaseHas('chief_users_invitations', [
            'id' => $invitationSentByUser->id,
            'inviter_id' => null,
        ]);

        $retainedInvitation = $invitationSentByUser->fresh();
        $mail = (new InvitationMail($retainedInvitation))->toMail('invitee@example.com');

        $this->assertSame($user->firstname, $retainedInvitation->inviter_snapshot['firstname']);
        $this->assertStringContainsString($user->firstname, $mail->render());
    }

    public function test_author_cannot_delete_a_user(): void
    {
        $user = $this->admin();

        $this->asAuthor()->delete(route('chief.back.users.destroy', $user->id));

        $this->assertDatabaseHas('chief_users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin, 'chief')
            ->delete(route('chief.back.users.destroy', $admin->id));

        $response->assertSessionHas('messages.error', 'U kan uw eigen account niet verwijderen.');
        $this->assertDatabaseHas('chief_users', ['id' => $admin->id]);
    }

    public function test_only_a_developer_can_delete_another_developer(): void
    {
        $developer = $this->developer();

        $response = $this->asAdmin()
            ->delete(route('chief.back.users.destroy', $developer->id));

        $response->assertRedirect(route('chief.back.dashboard'))
            ->assertSessionHas('messages.error');
        $this->assertDatabaseHas('chief_users', ['id' => $developer->id]);

        $deletingDeveloper = $this->developer();

        $this->actingAs($deletingDeveloper, 'chief')
            ->delete(route('chief.back.users.destroy', $developer->id))
            ->assertRedirect(route('chief.back.users.index'));

        $this->assertDatabaseMissing('chief_users', ['id' => $developer->id]);
    }

    public function test_missing_user_returns_not_found(): void
    {
        $this->asAdmin()
            ->delete(route('chief.back.users.destroy', 999999))
            ->assertNotFound();
    }
}
