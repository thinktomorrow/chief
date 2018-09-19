<?php

namespace Thinktomorrow\Chief\Users\Invites\Application;

use Thinktomorrow\Chief\Users\Invites\Events\InviteDenied;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\Common\State\StateException;
use Thinktomorrow\Chief\Users\Invites\InvitationState;

class DenyInvite
{
    public function handle(Invitation $invitation)
    {
        try {
            DB::beginTransaction();

            (new InvitationState($invitation))->apply('deny');

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
