<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Users\Invites\Application;

use Thinktomorrow\Chief\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\Users\User;
use Thinktomorrow\Chief\Users\Invites\InvitationState;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\States\State\StateException;

class InviteUser
{
    public function handle(User $invitee, User $inviter)
    {
        try {
            DB::beginTransaction();

            $invitation = Invitation::make((string) $invitee->id, (string) $inviter->id);

            (new InvitationState($invitation))->apply('invite');

            event(new UserInvited($invitation->id));

            DB::commit();

            return;
        } catch (StateException $e) {
            // exception is thrown if state transfer is already done
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
