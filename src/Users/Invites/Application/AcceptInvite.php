<?php

namespace Thinktomorrow\Chief\Users\Invites\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\Common\State\StateException;
use Thinktomorrow\Chief\Users\Invites\InvitationState;
use Thinktomorrow\Chief\Users\Invites\Events\InviteAccepted;

class AcceptInvite
{
    public function handle(Invitation $invitation)
    {
        try {
            DB::beginTransaction();

            (new InvitationState($invitation))->apply('accept');

            event(new InviteAccepted($invitation->id));

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
