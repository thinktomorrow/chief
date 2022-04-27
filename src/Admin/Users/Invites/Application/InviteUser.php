<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\Admin\Users\Invites\Invitation;
use Thinktomorrow\Chief\Admin\Users\Invites\InvitationState;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

class InviteUser
{
    /**
     * @return void
     */
    public function handle(User $invitee, User $inviter)
    {
        try {
            DB::beginTransaction();

            /** @var StatefulContract $invitation */
            $invitation = Invitation::make((string)$invitee->id, (string)$inviter->id);

            $stateMachine = StateMachine::fromConfig($invitation, $invitation->getStateConfig(InvitationState::KEY));
            $stateMachine->apply('invite');

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
