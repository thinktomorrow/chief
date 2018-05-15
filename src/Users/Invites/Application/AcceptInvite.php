<?php

namespace Chief\Users\Invites\Application;

use Illuminate\Support\Facades\DB;
use Chief\Users\Invites\Invitation;
use Chief\Common\State\StateException;
use Chief\Users\Invites\InvitationState;
use Chief\Users\Invites\Events\InviteAccepted;

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