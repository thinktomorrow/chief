<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Users\Invites\Application;

use Thinktomorrow\Chief\Users\Invites\Events\InviteDenied;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\States\State\StateException;
use Thinktomorrow\Chief\Users\Invites\InvitationState;

class DenyInvite
{
    public function handle(Invitation $invitation)
    {
        try {
            DB::beginTransaction();

            InvitationState::make($invitation)->apply('deny');

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
