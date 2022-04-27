<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\InviteDenied;
use Thinktomorrow\Chief\Admin\Users\Invites\Invitation;
use Thinktomorrow\Chief\Admin\Users\Invites\InvitationState;
use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;

class DenyInvite
{
    /**
     * @return void
     */
    public function handle(Invitation $invitation)
    {
        try {
            DB::beginTransaction();

            $stateMachine = StateMachine::fromConfig($invitation, $invitation->getStateConfig(InvitationState::KEY));
            $stateMachine->apply('deny');

            event(new InviteDenied($invitation->id));

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
